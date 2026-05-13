<?php
declare(strict_types=1);

namespace Modules\Authentication\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Modules\Authentication\Models\User;

/**
 * Service TwoFactorService
 *
 * Gère l'authentification à deux facteurs (2FA) avec Google Authenticator.
 * Inclut l'activation, la vérification, et les codes de récupération.
 *
 * @package Modules\Authentication\Services
 */
class TwoFactorService
{
    /**
     * Instance de Google2FA
     *
     * @var Google2FA
     */
    protected Google2FA $google2fa;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Active la 2FA pour un utilisateur
     *
     * @param User $user
     * @return array
     */
    public function enable(User $user): array
    {
        if (!config('authentication.two_factor.enabled', true)) {
            return [
                'success' => false,
                'message' => 'L\'authentification à deux facteurs n\'est pas activée.',
            ];
        }

        if ($this->isEnabled($user)) {
            return [
                'success' => false,
                'message' => 'La 2FA est déjà activée pour ce compte.',
            ];
        }

        // Générer un nouveau secret
        $secret = $this->google2fa->generateSecretKey();

        // Générer l'URL d'activation
        $companyName = config('app.name', 'LiveGood');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl($companyName, $user->email, $secret);

        // Stocker temporairement le secret
        Cache::put("2fa:{$user->id}:secret", $secret, now()->addMinutes(30));

        return [
            'success' => true,
            'data' => [
                'secret' => $secret,
                'qr_code_url' => $qrCodeUrl,
                'recovery_codes' => $this->generateRecoveryCodes(),
            ],
        ];
    }

    /**
     * Confirme l'activation de la 2FA
     *
     * @param User $user
     * @param string $code
     * @return array
     */
    public function confirm(User $user, string $code): array
    {
        $secret = Cache::get("2fa:{$user->id}:secret");

        if (!$secret) {
            return [
                'success' => false,
                'message' => 'Aucune activation 2FA en cours.',
            ];
        }

        // Vérifier le code
        if (!$this->verifyCode($secret, $code)) {
            return [
                'success' => false,
                'message' => 'Code 2FA invalide.',
            ];
        }

        // Sauvegarder le secret en base de données
        $recoveryCodes = $this->generateRecoveryCodes();
        $hashedRecoveryCodes = array_map(fn($code) => Hash::make($code), $recoveryCodes);

        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => json_encode($hashedRecoveryCodes),
            'two_factor_confirmed_at' => now(),
        ])->save();

        // Nettoyer le cache
        Cache::forget("2fa:{$user->id}:secret");

        \Log::channel('auth')->info('2FA activée', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [
            'success' => true,
            'message' => '2FA activée avec succès.',
            'recovery_codes' => $recoveryCodes,
        ];
    }

    /**
     * Désactive la 2FA
     *
     * @param User $user
     * @return array
     */
    public function disable(User $user): array
    {
        if (!$this->isEnabled($user)) {
            return [
                'success' => false,
                'message' => 'La 2FA n\'est pas activée pour ce compte.',
            ];
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        \Log::channel('auth')->info('2FA désactivée', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [
            'success' => true,
            'message' => '2FA désactivée avec succès.',
        ];
    }

    /**
     * Vérifie si la 2FA est activée
     *
     * @param User $user
     * @return bool
     */
    public function isEnabled(User $user): bool
    {
        return !is_null($user->two_factor_secret) 
            && !is_null($user->two_factor_confirmed_at);
    }

    /**
     * Vérifie un code 2FA
     *
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function verifyCode(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Vérifie le challenge 2FA pour une session
     *
     * @param string $code
     * @param bool $remember
     * @return array
     */
    public function verifyChallenge(string $code, bool $remember = false): array
    {
        $userId = session('2fa:user:id');

        if (!$userId) {
            return [
                'success' => false,
                'message' => 'Session 2FA expirée.',
            ];
        }

        $user = User::find($userId);

        if (!$user || !$this->isEnabled($user)) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé ou 2FA non activée.',
            ];
        }

        $secret = decrypt($user->two_factor_secret);

        // Vérifier le code 2FA
        if ($this->verifyCode($secret, $code)) {
            session(['2fa.verified' => $user->id]);

            if ($remember) {
                session(['2fa.remember' => true]);
            }

            // Nettoyer les tentatives
            Cache::forget("2fa:attempts:{$userId}");

            return [
                'success' => true,
                'message' => 'Code 2FA validé.',
                'token' => $this->generateAuthToken($user),
            ];
        }

        // Vérifier les codes de récupération
        $recoveryCodes = json_decode($user->two_factor_recovery_codes, true);

        foreach ($recoveryCodes as $index => $hashedCode) {
            if (Hash::check($code, $hashedCode)) {
                // Supprimer le code utilisé
                unset($recoveryCodes[$index]);
                $user->two_factor_recovery_codes = json_encode(array_values($recoveryCodes));
                $user->save();

                session(['2fa.verified' => $user->id]);

                return [
                    'success' => true,
                    'message' => 'Code de récupération utilisé. Veuillez générer de nouveaux codes.',
                    'token' => $this->generateAuthToken($user),
                ];
            }
        }

        // Limiter les tentatives
        $attemptsKey = "2fa:attempts:{$userId}";
        $attempts = Cache::get($attemptsKey, 0);
        $maxAttempts = config('authentication.two_factor.max_attempts', 5);

        if ($attempts >= $maxAttempts) {
            Cache::forget($attemptsKey);
            session()->forget('2fa:user:id');

            return [
                'success' => false,
                'message' => 'Trop de tentatives. Veuillez vous reconnecter.',
            ];
        }

        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(15));

        return [
            'success' => false,
            'message' => 'Code 2FA invalide.',
        ];
    }

    /**
     * Génère un token d'authentification après 2FA
     *
     * @param User $user
     * @return string
     */
    protected function generateAuthToken(User $user): string
    {
        // Révoquer l'ancien token si existant
        $user->tokens()->delete();

        // Créer un nouveau token
        $abilities = $user->isAffiliate() ? ['affiliate', 'read', 'write'] : ['member', 'read'];
        $token = $user->createToken('auth_token', $abilities);

        // Définir l'expiration
        $expiration = now()->addMinutes(config('authentication.jwt.ttl', 120));
        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return $token->plainTextToken;
    }

    /**
     * Génère des codes de récupération
     *
     * @param int $count
     * @param int $length
     * @return array
     */
    protected function generateRecoveryCodes(int $count = 8, int $length = 10): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
        }

        return $codes;
    }

    /**
     * Obtient les codes de récupération (non hachés)
     *
     * @param User $user
     * @return array|null
     */
    public function getRecoveryCodes(User $user): ?array
    {
        if (!$this->isEnabled($user)) {
            return null;
        }

        $hashedCodes = json_decode($user->two_factor_recovery_codes, true);

        // Les codes hachés ne peuvent pas être déchiffrés
        return null;
    }

    /**
     * Régénère les codes de récupération
     *
     * @param User $user
     * @return array
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        if (!$this->isEnabled($user)) {
            return [
                'success' => false,
                'message' => 'La 2FA n\'est pas activée pour ce compte.',
            ];
        }

        $newCodes = $this->generateRecoveryCodes();
        $hashedCodes = array_map(fn($code) => Hash::make($code), $newCodes);

        $user->forceFill([
            'two_factor_recovery_codes' => json_encode($hashedCodes),
        ])->save();

        \Log::channel('auth')->info('Codes de récupération 2FA régénérés', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [
            'success' => true,
            'message' => 'Codes de récupération régénérés avec succès.',
            'recovery_codes' => $newCodes,
        ];
    }
}
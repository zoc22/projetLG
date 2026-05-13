<?php
declare(strict_types=1);

namespace Modules\Authentication\Actions;

use Illuminate\Support\Facades\Cache;
use Modules\Authentication\DTO\LoginDTO;
use Modules\Authentication\Models\User;
use Modules\Authentication\Models\LoginHistory;

/**
 * Action LoginUser
 *
 * Gère la connexion d'un utilisateur existant.
 * Inclut la vérification des identifiants, la gestion des sessions,
 * et l'enregistrement de l'historique de connexion.
 *
 * @package Modules\Authentication\Actions
 */
class LoginUser
{
    /**
     * Préfixe du cache pour les tentatives échouées
     */
    private const FAILED_ATTEMPTS_PREFIX = 'login_failed_';

    /**
     * Nombre maximum de tentatives avant verrouillage
     */
    private int $maxAttempts;

    /**
     * Durée du verrouillage en minutes
     */
    private int $lockoutMinutes;

    public function __construct()
    {
        $this->maxAttempts = config('authentication.security.max_login_attempts', 5);
        $this->lockoutMinutes = config('authentication.security.lockout_time', 15);
    }

    /**
     * Exécute la connexion
     *
     * @param LoginDTO $dto
     * @return array{
     *     success: bool,
     *     message: string,
     *     user?: User,
     *     token?: string,
     *     requires_2fa?: bool
     * }
     */
    public function execute(LoginDTO $dto): array
    {
        // Vérifier le verrouillage du compte
        if ($this->isAccountLocked($dto->email)) {
            return [
                'success' => false,
                'message' => sprintf(
                    'Trop de tentatives. Veuillez réessayer dans %d minutes.',
                    $this->lockoutMinutes
                ),
            ];
        }

        // Rechercher l'utilisateur avec eager loading optimisé
        $user = User::with(['affiliate', 'subscription'])
            ->where('email', $dto->email)
            ->first();

        // Vérifier l'existence de l'utilisateur et le mot de passe
        if (!$user || !$this->verifyPassword($user, $dto->password)) {
            $this->recordFailedAttempt($dto->email);
            
            return [
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.',
            ];
        }

        // Réinitialiser les tentatives échouées
        $this->clearFailedAttempts($dto->email);

        // Vérifier le statut du compte
        $statusCheck = $this->checkAccountStatus($user);
        if (!$statusCheck['success']) {
            return $statusCheck;
        }

        // Vérifier si la 2FA est activée
        if ($this->isTwoFactorEnabled($user)) {
            return $this->handleTwoFactorChallenge($user, $dto);
        }

        // Procéder à la connexion complète
        return $this->completeLogin($user, $dto);
    }

    /**
     * Vérifie le mot de passe
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    private function verifyPassword(User $user, string $password): bool
    {
        return \Illuminate\Support\Facades\Hash::check($password, $user->password);
    }

    /**
     * Vérifie le statut du compte
     *
     * @param User $user
     * @return array{success: bool, message: string}
     */
    private function checkAccountStatus(User $user): array
    {
        if (!$user->canLogin()) {
            $messages = [
                'inactif' => 'Votre compte est inactif. Veuillez contacter le support.',
                'suspendu' => 'Votre compte a été suspendu temporairement.',
                'bloque' => 'Votre compte a été bloqué. Contactez le support.',
                'en_attente_verification' => 'Veuillez vérifier votre email avant de vous connecter.',
                'en_attente_approbation' => 'Votre compte est en attente d\'approbation.',
            ];
            
            return [
                'success' => false,
                'message' => $messages[$user->statut_compte->value] ?? 'Compte non disponible.',
            ];
        }

        return ['success' => true, 'message' => ''];
    }

    /**
     * Vérifie si la 2FA est activée
     *
     * @param User $user
     * @return bool
     */
    private function isTwoFactorEnabled(User $user): bool
    {
        return !is_null($user->two_factor_secret) 
            && !is_null($user->two_factor_confirmed_at);
    }

    /**
     * Gère le challenge 2FA
     *
     * @param User $user
     * @param LoginDTO $dto
     * @return array
     */
    private function handleTwoFactorChallenge(User $user, LoginDTO $dto): array
    {
        // Stocker l'ID utilisateur en session pour le challenge
        session(['2fa:user:id' => $user->id]);
        session(['2fa:remember' => $dto->remember]);

        return [
            'success' => true,
            'requires_2fa' => true,
            'message' => 'Code 2FA requis.',
            'user' => $user->makeHidden(['two_factor_secret', 'two_factor_recovery_codes']),
        ];
    }

    /**
     * Complète la connexion
     *
     * @param User $user
     * @param LoginDTO $dto
     * @return array
     */
    private function completeLogin(User $user, LoginDTO $dto): array
    {
        // Générer le token d'accès
        $token = $this->generateAccessToken($user);

        // Mettre à jour la dernière connexion
        $user->updateLastLogin($dto->ipAddress ?? request()->ip());

        // Gérer les sessions simultanées
        $this->manageConcurrentSessions($user);

        // Enregistrer l'historique
        $this->recordLoginHistory($user, $dto);

        return [
            'success' => true,
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $token,
            'requires_2fa' => false,
        ];
    }

    /**
     * Génère un token d'accès
     *
     * @param User $user
     * @return string
     */
    private function generateAccessToken(User $user): string
    {
        $abilities = $user->isAffiliate() 
            ? ['affiliate', 'read', 'write']
            : ['member', 'read'];

        $token = $user->createToken('auth_token', $abilities);
        
        // Définir l'expiration
        $expiration = now()->addMinutes(config('authentication.jwt.ttl', 120));
        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return $token->plainTextToken;
    }

    /**
     * Gère les sessions simultanées
     *
     * @param User $user
     * @return void
     */
    private function manageConcurrentSessions(User $user): void
    {
        $maxSessions = config('authentication.security.max_concurrent_sessions', 3);
        
        if ($maxSessions > 0) {
            $activeTokens = $user->tokens()
                ->where('expires_at', '>', now())
                ->count();
                
            if ($activeTokens >= $maxSessions) {
                $oldestToken = $user->tokens()
                    ->orderBy('created_at')
                    ->first();
                    
                $oldestToken?->delete();
            }
        }
    }

    /**
     * Enregistre l'historique de connexion
     *
     * @param User $user
     * @param LoginDTO $dto
     * @return void
     */
    private function recordLoginHistory(User $user, LoginDTO $dto): void
    {
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $dto->ipAddress ?? request()->ip(),
            'user_agent' => $dto->userAgent ?? request()->userAgent(),
            'login_at' => now(),
            'login_successful' => true,
        ]);
    }

    /**
     * Vérifie si le compte est verrouillé
     *
     * @param string $email
     * @return bool
     */
    private function isAccountLocked(string $email): bool
    {
        $cacheKey = self::FAILED_ATTEMPTS_PREFIX . md5($email);
        $attempts = Cache::get($cacheKey, 0);
        
        return $attempts >= $this->maxAttempts;
    }

    /**
     * Enregistre une tentative échouée
     *
     * @param string $email
     * @return void
     */
    private function recordFailedAttempt(string $email): void
    {
        $cacheKey = self::FAILED_ATTEMPTS_PREFIX . md5($email);
        $attempts = Cache::get($cacheKey, 0) + 1;
        
        if ($attempts >= $this->maxAttempts) {
            Cache::put($cacheKey, $attempts, now()->addMinutes($this->lockoutMinutes));
        } else {
            Cache::put($cacheKey, $attempts, now()->addMinutes(5));
        }

        // Journaliser la tentative échouée
        \Log::channel('auth')->warning('Tentative de connexion échouée', [
            'email' => $email,
            'ip' => request()->ip(),
            'attempts' => $attempts,
        ]);
    }

    /**
     * Nettoie les tentatives échouées
     *
     * @param string $email
     * @return void
     */
    private function clearFailedAttempts(string $email): void
    {
        $cacheKey = self::FAILED_ATTEMPTS_PREFIX . md5($email);
        Cache::forget($cacheKey);
    }
}
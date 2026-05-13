<?php
declare(strict_types=1);

namespace Modules\Authentication\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Authentication\Actions\AuthenticateUser;
use Modules\Authentication\Actions\LoginUser;
use Modules\Authentication\Actions\LogoutUser;
use Modules\Authentication\Actions\RegisterUser;
use Modules\Authentication\Actions\ResetPassword;
use Modules\Authentication\DTO\LoginDTO;
use Modules\Authentication\DTO\RegisterDTO;
use Modules\Authentication\Events\UserLoggedIn;
use Modules\Authentication\Events\UserLoggedOut;
use Modules\Authentication\Events\UserRegistered;
use Modules\Authentication\Models\User;
use Modules\Authentication\Models\PasswordReset;

/**
 * Service AuthService
 *
 * Service principal d'authentification.
 * Orchestre les actions et la logique métier d'authentification.
 *
 * @package Modules\Authentication\Services
 */
class AuthService
{
    /**
     * Action d'authentification
     *
     * @var AuthenticateUser
     */
    protected AuthenticateUser $authenticateUser;

    /**
     * Action de connexion
     *
     * @var LoginUser
     */
    protected LoginUser $loginUser;

    /**
     * Action de déconnexion
     *
     * @var LogoutUser
     */
    protected LogoutUser $logoutUser;

    /**
     * Action d'inscription
     *
     * @var RegisterUser
     */
    protected RegisterUser $registerUser;

    /**
     * Action de réinitialisation
     *
     * @var ResetPassword
     */
    protected ResetPassword $resetPassword;

    /**
     * Constructeur
     *
     * @param AuthenticateUser $authenticateUser
     * @param LoginUser $loginUser
     * @param LogoutUser $logoutUser
     * @param RegisterUser $registerUser
     * @param ResetPassword $resetPassword
     */
    public function __construct(
        AuthenticateUser $authenticateUser,
        LoginUser $loginUser,
        LogoutUser $logoutUser,
        RegisterUser $registerUser,
        ResetPassword $resetPassword
    ) {
        $this->authenticateUser = $authenticateUser;
        $this->loginUser = $loginUser;
        $this->logoutUser = $logoutUser;
        $this->registerUser = $registerUser;
        $this->resetPassword = $resetPassword;
    }

    /**
     * Connexion utilisateur
     *
     * @param LoginDTO $dto
     * @return array
     */
    public function login(LoginDTO $dto): array
    {
        $result = $this->loginUser->execute($dto);

        if ($result['success'] && isset($result['user'])) {
            event(new UserLoggedIn(
                $result['user'],
                $dto->ipAddress,
                $dto->userAgent,
                $dto->remember
            ));
        }

        return $result;
    }

    /**
     * Inscription utilisateur
     *
     * @param RegisterDTO $dto
     * @return array
     */
    public function register(RegisterDTO $dto): array
    {
        $result = $this->registerUser->execute($dto);

        if ($result['success'] && isset($result['user'])) {
            event(new UserRegistered($result['user'], $dto->parrainCode));
        }

        return $result;
    }

    /**
     * Déconnexion utilisateur
     *
     * @param User $user
     * @return array
     */
    public function logout(User $user): array
    {
        $result = $this->logoutUser->execute($user);

        if ($result['success']) {
            event(new UserLoggedOut($user));
        }

        return $result;
    }

    /**
     * Rafraîchit le token d'accès
     *
     * @param Request $request
     * @return array
     */
    public function refreshToken(Request $request): array
    {
        $user = $request->user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
            ];
        }

        // Révoquer l'ancien token
        $request->user()->currentAccessToken()->delete();

        // Créer un nouveau token
        $abilities = $user->isAffiliate() ? ['affiliate', 'read', 'write'] : ['member', 'read'];
        $token = $user->createToken('auth_token', $abilities);

        // Définir l'expiration
        $expiration = now()->addMinutes(config('authentication.jwt.ttl', 120));
        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return [
            'success' => true,
            'token' => $token->plainTextToken,
            'expires_in' => $expiration->diffInSeconds(now()),
        ];
    }

    /**
     * Demande de réinitialisation de mot de passe
     *
     * @param string $email
     * @return array
     */
    public function forgotPassword(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Ne pas révéler que l'email n'existe pas
            return ['success' => true];
        }

        // Générer un token unique
        $token = Str::random(60);

        // Supprimer les anciens tokens
        PasswordReset::where('email', $email)->delete();

        // Créer le token avec expiration
        $expiresAt = now()->addMinutes(config('authentication.password.reset_token_expiry', 60));

        PasswordReset::create([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        // Envoyer l'email
        $this->sendResetLinkEmail($user, $token);

        return ['success' => true];
    }

    /**
     * Envoie l'email de réinitialisation
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    protected function sendResetLinkEmail(User $user, string $token): void
    {
        $resetUrl = config('app.frontend_url') . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);

        try {
            \Mail::send('authentication::emails.reset-password', [
                'user' => $user,
                'resetUrl' => $resetUrl,
                'expiresIn' => config('authentication.password.reset_token_expiry', 60),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->getFullNameAttribute())
                    ->subject('Réinitialisation de votre mot de passe LiveGood')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email réinitialisation', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Réinitialise le mot de passe
     *
     * @param string $email
     * @param string $token
     * @param string $newPassword
     * @return array
     */
    public function resetPassword(string $email, string $token, string $newPassword): array
    {
        return $this->resetPassword->execute($email, $token, $newPassword);
    }

    /**
     * Change le mot de passe (utilisateur connecté)
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return array
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): array
    {
        // Vérifier le mot de passe actuel
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect.',
            ];
        }

        // Vérifier que le nouveau mot de passe est différent
        if (Hash::check($newPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
            ];
        }

        // Mettre à jour le mot de passe
        DB::transaction(function () use ($user, $newPassword) {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            // Révoquer tous les tokens sauf celui actuel (optionnel)
            // $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

            \Log::channel('auth')->info('Mot de passe changé', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
            ]);
        });

        return [
            'success' => true,
            'message' => 'Mot de passe modifié avec succès.',
        ];
    }

    /**
     * Vérifie l'email
     *
     * @param string $userId
     * @param string $hash
     * @return array
     */
    public function verifyEmail(string $userId, string $hash): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
            ];
        }

        // Vérifier le hash
        if ($hash !== sha1($user->email)) {
            return [
                'success' => false,
                'message' => 'Lien de vérification invalide.',
            ];
        }

        // Vérifier si déjà vérifié
        if ($user->hasVerifiedEmail()) {
            return [
                'success' => true,
                'message' => 'Email déjà vérifié.',
            ];
        }

        // Marquer comme vérifié
        $user->markEmailAsVerified();

        \Log::channel('auth')->info('Email vérifié', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [
            'success' => true,
            'message' => 'Email vérifié avec succès.',
        ];
    }

    /**
     * Renvoie l'email de vérification
     *
     * @param User $user
     * @return array
     */
    public function resendVerificationEmail(User $user): array
    {
        if ($user->hasVerifiedEmail()) {
            return [
                'success' => false,
                'message' => 'Email déjà vérifié.',
            ];
        }

        // Vérifier le rate limiting (3 envois par heure max)
        $cacheKey = 'email_verification_' . $user->id;
        $attempts = cache()->get($cacheKey, 0);

        if ($attempts >= 3) {
            return [
                'success' => false,
                'message' => 'Trop de demandes. Veuillez réessayer plus tard.',
            ];
        }

        // Envoyer l'email
        try {
            $verificationUrl = config('app.frontend_url') . '/verify-email/' . $user->id . '/' . sha1($user->email);

            \Mail::send('authentication::emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->getFullNameAttribute())
                    ->subject('Vérification de votre adresse email LiveGood')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            cache()->put($cacheKey, $attempts + 1, now()->addHours(1));

            return [
                'success' => true,
                'message' => 'Email de vérification envoyé.',
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email vérification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email.',
            ];
        }
    }

    /**
     * Vérifie la validité d'un token de réinitialisation
     *
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function verifyResetToken(string $email, string $token): bool
    {
        $resetRecord = PasswordReset::where('email', $email)->first();

        if (!$resetRecord || $resetRecord->isExpired()) {
            return false;
        }

        return Hash::check($token, $resetRecord->token);
    }
}
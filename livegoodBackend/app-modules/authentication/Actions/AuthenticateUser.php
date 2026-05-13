<?php
declare(strict_types=1);

namespace Modules\Authentication\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Modules\Authentication\Models\User;
use Modules\Authentication\DTO\LoginDTO;
use Modules\Authentication\Enums\UserStatusEnum;

/**
 * Action AuthenticateUser
 *
 * Authentifie un utilisateur avec ses credentials.
 * Vérifie le statut du compte et gère les tentatives échouées.
 * Implémente le rate limiting pour prévenir les attaques par brute force.
 *
 * @package Modules\Authentication\Actions
 */
class AuthenticateUser
{
    /**
     * Cache key pour le verrouillage de compte
     */
    private const LOCKOUT_CACHE_PREFIX = 'login_lockout_';

    /**
     * Nombre maximum de tentatives avant verrouillage
     *
     * @var int
     */
    private int $maxAttempts;

    /**
     * Durée du verrouillage (minutes)
     *
     * @var int
     */
    private int $lockoutMinutes;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->maxAttempts = config('authentication.security.max_login_attempts', 5);
        $this->lockoutMinutes = config('authentication.security.lockout_time', 15);
    }

    /**
     * Exécute l'authentification
     *
     * @param LoginDTO $dto
     * @return array{user: User, success: bool, message: string, token?: string}
     */
    public function execute(LoginDTO $dto): array
    {
        // Vérifier le verrouillage du compte
        if ($this->isAccountLocked($dto->email)) {
            return [
                'success' => false,
                'message' => 'Trop de tentatives. Veuillez réessayer dans ' . $this->lockoutMinutes . ' minutes.',
                'user' => null,
            ];
        }

        // Rechercher l'utilisateur avec eager loading optimisé
        $user = User::with(['affiliate'])
            ->where('email', $dto->email)
            ->first();

        // Vérifier si l'utilisateur existe et le mot de passe est correct
        if (!$user || !Hash::check($dto->password, $user->password)) {
            $this->recordFailedAttempt($dto->email);
            
            return [
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.',
                'user' => null,
            ];
        }

        // Vérifier si l'utilisateur peut se connecter
        if (!$user->canLogin()) {
            $statusMessage = $this->getStatusMessage($user->statut_compte);
            
            return [
                'success' => false,
                'message' => $statusMessage,
                'user' => null,
            ];
        }

        // Réinitialiser les tentatives échouées
        $this->clearFailedAttempts($dto->email);

        // Mettre à jour la dernière connexion
        $user->updateLastLogin($dto->ipAddress ?? request()->ip());

        // Générer le token d'accès
        $token = $this->generateAccessToken($user);

        // Vérifier les sessions simultanées
        $this->manageConcurrentSessions($user);

        return [
            'success' => true,
            'message' => 'Authentification réussie.',
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Génère un token d'accès pour l'utilisateur
     *
     * @param User $user
     * @return string
     */
    private function generateAccessToken(User $user): string
    {
        // Abilities basées sur le rôle
        $abilities = $this->getTokenAbilities($user);
        
        // Créer le token avec une expiration
        $token = $user->createToken('auth_token', $abilities);
        
        // Définir l'expiration du token
        $expiration = now()->addMinutes(config('authentication.jwt.ttl', 120));
        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return $token->plainTextToken;
    }

    /**
     * Obtient les abilities du token selon le rôle
     *
     * @param User $user
     * @return array
     */
    private function getTokenAbilities(User $user): array
    {
        $abilities = ['*'];
        
        if ($user->type_utilisateur->value === 'member') {
            $abilities = ['read', 'shop', 'profile'];
        } elseif ($user->type_utilisateur->value === 'affiliate') {
            $abilities = ['read', 'shop', 'profile', 'genealogy', 'commissions'];
        }
        
        return $abilities;
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
                // Révoquer le plus ancien token
                $oldestToken = $user->tokens()
                    ->orderBy('created_at')
                    ->first();
                    
                if ($oldestToken) {
                    $oldestToken->delete();
                }
            }
        }
    }

    /**
     * Vérifie si le compte est verrouillé
     *
     * @param string $email
     * @return bool
     */
    private function isAccountLocked(string $email): bool
    {
        $cacheKey = self::LOCKOUT_CACHE_PREFIX . md5($email);
        return Cache::has($cacheKey);
    }

    /**
     * Enregistre une tentative échouée
     *
     * @param string $email
     * @return void
     */
    private function recordFailedAttempt(string $email): void
    {
        $cacheKey = self::LOCKOUT_CACHE_PREFIX . md5($email);
        $attempts = Cache::get($cacheKey, 0) + 1;
        
        Cache::put($cacheKey, $attempts, now()->addMinutes($this->lockoutMinutes));
        
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
        $cacheKey = self::LOCKOUT_CACHE_PREFIX . md5($email);
        Cache::forget($cacheKey);
    }

    /**
     * Obtient le message de statut du compte
     *
     * @param UserStatusEnum $status
     * @return string
     */
    private function getStatusMessage(UserStatusEnum $status): string
    {
        return match($status) {
            UserStatusEnum::ACTIF => 'Compte actif.',
            UserStatusEnum::INACTIF => 'Votre compte est inactif. Veuillez contacter le support.',
            UserStatusEnum::SUSPENDU => 'Votre compte a été suspendu temporairement.',
            UserStatusEnum::BLOQUE => 'Votre compte a été bloqué. Contactez le support.',
            UserStatusEnum::EN_ATTENTE_VERIFICATION => 'Veuillez vérifier votre email avant de vous connecter.',
            UserStatusEnum::EN_ATTENTE_APPROBATION => 'Votre compte est en attente d\'approbation.',
        };
    }
}
<?php
declare(strict_types=1);

namespace Modules\Authentication\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Modules\Authentication\Models\User;
use Modules\Authentication\Enums\UserRoleEnum;
use Modules\Authentication\Enums\UserStatusEnum;
use Modules\Authentication\Events\UserRegistered;

/**
 * Service SocialAuthService
 *
 * Gère l'authentification via les réseaux sociaux (Google, Facebook, etc.).
 * Crée ou connecte un utilisateur existant via son provider.
 *
 * @package Modules\Authentication\Services
 */
class SocialAuthService
{
    /**
     * Liste des providers supportés
     *
     * @var array
     */
    protected array $supportedProviders = ['google', 'facebook', 'github'];

    /**
     * Redirige vers le provider pour authentification
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        if (!$this->isProviderSupported($provider)) {
            abort(400, "Provider '{$provider}' not supported.");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Authentifie ou crée un utilisateur via le provider
     *
     * @param string $provider
     * @return array
     */
    public function handleProviderCallback(string $provider): array
    {
        if (!$this->isProviderSupported($provider)) {
            return [
                'success' => false,
                'message' => "Provider '{$provider}' not supported.",
            ];
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            \Log::error('Erreur authentification sociale', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'authentification avec ' . ucfirst($provider),
            ];
        }

        // Vérifier si l'utilisateur existe déjà via ce provider
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Mettre à jour les informations sociales
            $this->updateSocialInfo($user, $provider, $socialUser);

            return [
                'success' => true,
                'user' => $user,
                'token' => $this->generateAuthToken($user),
                'is_new' => false,
            ];
        }

        // Créer un nouvel utilisateur
        return $this->createUserFromSocial($provider, $socialUser);
    }

    /**
     * Vérifie si le provider est supporté
     *
     * @param string $provider
     * @return bool
     */
    protected function isProviderSupported(string $provider): bool
    {
        return in_array($provider, $this->supportedProviders);
    }

    /**
     * Met à jour les informations sociales de l'utilisateur
     *
     * @param User $user
     * @param string $provider
     * @param mixed $socialUser
     * @return void
     */
    protected function updateSocialInfo(User $user, string $provider, $socialUser): void
    {
        $socialProvider = "{$provider}_id";
        
        if (empty($user->$socialProvider)) {
            $user->$socialProvider = $socialUser->getId();
            
            // Mettre à jour l'avatar si disponible
            if ($socialUser->getAvatar()) {
                $user->avatar = $socialUser->getAvatar();
            }
            
            $user->save();
        }
    }

    /**
     * Crée un utilisateur à partir des données sociales
     *
     * @param string $provider
     * @param mixed $socialUser
     * @return array
     */
    protected function createUserFromSocial(string $provider, $socialUser): array
    {
        $nameParts = $this->splitFullName($socialUser->getName());

        return DB::transaction(function () use ($provider, $socialUser, $nameParts) {
            // Créer l'utilisateur
            $user = User::create([
                'nom' => $nameParts['last'],
                'prenom' => $nameParts['first'],
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(bin2hex(random_bytes(16))),
                'type_utilisateur' => UserRoleEnum::MEMBER->value,
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);

            // Créer la souscription par défaut
            $user->subscription()->create([
                'type' => 'mensuel',
                'montant' => 9.95,
                'date_debut' => now(),
                'date_fin' => now()->addMonth(),
                'statut' => 'ACTIF',
                'auto_renew' => false,
            ]);

            // Dispatcher l'événement
            event(new UserRegistered($user));

            \Log::channel('auth')->info('Nouvel utilisateur via réseau social', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $provider,
            ]);

            return [
                'success' => true,
                'user' => $user,
                'token' => $this->generateAuthToken($user),
                'is_new' => true,
            ];
        });
    }

    /**
     * Génère un token d'authentification
     *
     * @param User $user
     * @return string
     */
    protected function generateAuthToken(User $user): string
    {
        $abilities = ['member', 'read'];
        
        $token = $user->createToken('social_auth_token', $abilities);
        
        $expiration = now()->addMinutes(config('authentication.jwt.ttl', 120));
        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return $token->plainTextToken;
    }

    /**
     * Sépare le nom complet en prénom et nom
     *
     * @param string|null $fullName
     * @return array
     */
    protected function splitFullName(?string $fullName): array
    {
        if (empty($fullName)) {
            return ['first' => 'Utilisateur', 'last' => 'Social'];
        }

        $parts = explode(' ', trim($fullName), 2);
        
        return [
            'first' => $parts[0] ?? 'Utilisateur',
            'last' => $parts[1] ?? 'Social',
        ];
    }

    /**
     * Lie un compte social à un utilisateur existant
     *
     * @param User $user
     * @param string $provider
     * @param string $providerId
     * @return array
     */
    public function linkSocialAccount(User $user, string $provider, string $providerId): array
    {
        $field = $provider . '_id';

        // Vérifier si ce compte social n'est pas déjà lié à un autre utilisateur
        $existingUser = User::where($field, $providerId)->first();
        
        if ($existingUser && $existingUser->id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Ce compte social est déjà lié à un autre utilisateur.',
            ];
        }

        $user->$field = $providerId;
        $user->save();

        \Log::channel('auth')->info('Compte social lié', [
            'user_id' => $user->id,
            'provider' => $provider,
        ]);

        return [
            'success' => true,
            'message' => 'Compte ' . ucfirst($provider) . ' lié avec succès.',
        ];
    }
}
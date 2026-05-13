<?php
declare(strict_types=1);

namespace Modules\Authentication\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Modules\Authentication\Models\User;
use Modules\Authentication\DTO\RegisterDTO;
use Modules\Authentication\Events\UserRegistered;
use Modules\Authentication\Enums\UserRoleEnum;
use Modules\Authentication\Enums\UserStatusEnum;
use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Services\AffiliateService;

/**
 * Action RegisterUser
 *
 * Enregistre un nouvel utilisateur dans le système.
 * Gère la création du compte, l'affiliation optionnelle,
 * et l'envoi des emails de bienvenue.
 *
 * @package Modules\Authentication\Actions
 */
class RegisterUser
{
    /**
     * Constructeur
     *
     * @param AffiliateService $affiliateService
     */
    public function __construct(
        private AffiliateService $affiliateService
    ) {}

    /**
     * Exécute l'inscription
     *
     * @param RegisterDTO $dto
     * @return array{user: User, affiliate: Affiliate|null, success: bool, message: string, token?: string}
     */
    public function execute(RegisterDTO $dto): array
    {
        // Valider les données (déjà fait dans le DTO)
        if (!$dto->acceptTerms) {
            return [
                'success' => false,
                'message' => 'Vous devez accepter les conditions générales.',
                'user' => null,
                'affiliate' => null,
            ];
        }

        // Démarrer une transaction pour assurer l'intégrité
        return DB::transaction(function () use ($dto) {
            // Créer l'utilisateur
            $userData = $dto->toUserArray();
            $userData['password'] = Hash::make($dto->password);
            
            $user = User::create($userData);

            // Créer un token d'accès
            $token = $this->generateAccessToken($user);

            // Créer l'affilié si demandé
            $affiliate = null;
            if ($dto->wantsToBeAffiliate()) {
                $affiliate = $this->createAffiliate($user, $dto);
            }

            // Créer la souscription
            $this->createSubscription($user, $dto);

            // Dispatch de l'événement d'inscription
            Event::dispatch(new UserRegistered($user, $dto->parrainCode));

            // Envoyer l'email de vérification
            if (config('authentication.security.force_email_verification')) {
                $user->sendEmailVerificationNotification();
            }

            // Journaliser l'inscription
            \Log::channel('auth')->info('Nouvel utilisateur inscrit', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $dto->role,
                'ip' => $dto->ipAddress,
                'has_referral' => $dto->hasReferralCode(),
            ]);

            return [
                'success' => true,
                'message' => 'Inscription réussie. ' . 
                    (config('authentication.security.force_email_verification') 
                        ? 'Un email de vérification vous a été envoyé.' 
                        : 'Vous pouvez maintenant vous connecter.'),
                'user' => $user,
                'affiliate' => $affiliate,
                'token' => $token,
            ];
        });
    }

    /**
     * Génère un token d'accès
     *
     * @param User $user
     * @return string|null
     */
    private function generateAccessToken(User $user): ?string
    {
        try {
            $token = $user->createToken('auth_token', ['*']);
            return $token->plainTextToken;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du token', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Crée l'affilié pour l'utilisateur
     *
     * @param User $user
     * @param RegisterDTO $dto
     * @return Affiliate|null
     */
    private function createAffiliate(User $user, RegisterDTO $dto): ?Affiliate
    {
        try {
            $parrain = null;
            
            // Trouver le parrain si code fourni
            if ($dto->hasReferralCode()) {
                $parrain = Affiliate::where('code_affiliation', $dto->parrainCode)->first();
            }

            return $this->affiliateService->createAffiliateProfile(
                $user->id,
                $user->prenom . '_' . substr($user->nom, 0, 3) . '_' . rand(100, 999)
            );
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'affilié', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Crée la souscription de l'utilisateur
     *
     * @param User $user
     * @param RegisterDTO $dto
     * @return void
     */
    private function createSubscription(User $user, RegisterDTO $dto): void
    {
        $amount = $dto->subscriptionType === 'annuel' ? 139.95 : 9.95;
        $expiryDate = $dto->subscriptionType === 'annuel' 
            ? now()->addYear() 
            : now()->addMonth();

        $user->subscription()->create([
            'type' => $dto->subscriptionType,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $expiryDate,
            'auto_renew' => true,
        ]);
    }
}
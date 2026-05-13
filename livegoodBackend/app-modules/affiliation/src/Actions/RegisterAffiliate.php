<?php

namespace Modules\Affiliation\Actions;

use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Services\AffiliateService;
use Modules\Affiliation\Services\SubscriptionService;
use Modules\Affiliation\Enums\SubscriptionTypeEnum;
use Illuminate\Support\Facades\DB;

/**
 * Action complexe pour enregistrer un nouvel affilié avec son abonnement.
 */
class RegisterAffiliate
{
    public function __construct(
        protected AffiliateService $affService,
        protected SubscriptionService $subService
    ) {}

    public function execute(string $userId, string $username, SubscriptionTypeEnum $plan): Affiliate
    {
        return DB::transaction(function() use ($userId, $username, $plan) {
            // 1. Création Profil
            $affiliate = $this->affService->createAffiliateProfile($userId, $username);
            
            // 2. Création Abonnement
            $this->subService->subscribe($userId, $plan);
            
            // 3. Activation automatique (si le paiement a été simulé comme réussi)
            $this->affService->activateAffiliate($affiliate->id);

            return $affiliate;
        });
    }
}

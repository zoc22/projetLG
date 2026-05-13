<?php

namespace Modules\Affiliation\Services;

use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Enums\AffiliationStatusEnum;
use Illuminate\Support\Str;

/**
 * Service central pour la gestion des affiliés.
 */
class AffiliateService
{
    /**
     * Crée un profil d'affilié pour un utilisateur existant.
     */
    public function createAffiliateProfile(string $userId, string $username): Affiliate
    {
        return Affiliate::create([
            'user_id'             => $userId,
            'username_canonical' => Str::slug($username),
            'referral_code'      => strtoupper(Str::random(10)),
            'status'             => AffiliationStatusEnum::PENDING,
            'rank'               => \Modules\Affiliation\Enums\RankEnum::UNRANKED,
        ]);
    }

    /**
     * Active un affilié après validation du premier paiement.
     */
    public function activateAffiliate(string $affiliateId): void
    {
        $affiliate = Affiliate::findOrFail($affiliateId);
        $affiliate->update(['status' => AffiliationStatusEnum::ACTIVE]);
    }

    /**
     * Récupère le résumé business de l'affilié.
     */
    public function getBusinessSummary(string $userId): array
    {
        $affiliate = Affiliate::where('user_id', $userId)->firstOrFail();
        
        return [
            'rank'          => $affiliate->rank->value,
            'status'        => $affiliate->status->value,
            'earnings'      => $affiliate->total_commissions,
            'current_links' => $affiliate->links()->count(),
        ];
    }
}

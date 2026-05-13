<?php

namespace Modules\Affiliation\Services;

use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Models\RankHistory;
use Modules\Affiliation\Enums\RankEnum;

/**
 * Service pour la logique de progression des rangs.
 */
class RankService
{
    /**
     * Evalue si l'affilié doit monter de rang.
     */
    public function evaluateQualification(string $affiliateId): void
    {
        $affiliate = Affiliate::findOrFail($affiliateId);
        $currentRank = $affiliate->rank;

        // Simulation de logique LiveGood
        // Bronze: 2 membres actifs directs
        // On récupère via la relation directe de Users (sponsor_id)
        $activeDirects = \Modules\Authentication\Models\User::where('sponsor_id', $affiliate->user_id)
            ->where('status', \Modules\Authentication\Enums\UserStatusEnum::ACTIVE)
            ->count();

        if ($currentRank === RankEnum::UNRANKED && $activeDirects >= 2) {
            $this->promote($affiliate, RankEnum::BRONZE);
        }
    }

    protected function promote(Affiliate $affiliate, RankEnum $newRank): void
    {
        $oldRank = $affiliate->rank;
        $affiliate->update(['rank' => $newRank]);

        RankHistory::create([
            'affiliate_id' => $affiliate->id,
            'old_rank'     => $oldRank->value,
            'new_rank'     => $newRank->value,
            'reason_metadata' => ['trigger' => 'active_referrals_count']
        ]);

        // Event: RankUpgraded
    }
}

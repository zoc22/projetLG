<?php

namespace Modules\Affiliation\Actions;

use Modules\Affiliation\Models\Affiliate;

/**
 * Calcul des commissions (Base Unilevel & Matrice).
 * Note: Très complexe, ici on simule l'agrégation.
 */
class CalculateCommissions
{
    public function execute(string $affiliateId): float
    {
        $affiliate = Affiliate::findOrFail($affiliateId);
        
        // Simuler calcul sur 10 niveaux (Fast Start) + Matrice 2x15
        $commissionVolume = 450.75; 

        $affiliate->increment('total_commissions', $commissionVolume);
        $affiliate->increment('pending_balance', $commissionVolume);

        return $commissionVolume;
    }
}

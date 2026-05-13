<?php

namespace Modules\Commission\Services;

use Modules\Commission\Actions\CalculateFastStartBonus;
use Modules\Commission\Enums\CommissionTypeEnum;
use Illuminate\Support\Facades\Log;

/**
 * Gère la logique spécifique au Fast Start Bonus (Hebdomadaire).
 */
class FastStartService
{
    public function __construct(
        protected CalculateFastStartBonus $calculateAction,
        protected CommissionService $commissionService
    ) {}

    /**
     * Calcule le Fast Start pour un membre nouvellement inscrit.
     * Cette commission est payée au sponsor et à son upline.
     */
    public function processNewEnrollment(string $newUserId, string $sponsorId): void
    {
        Log::info("Calcul Fast Start pour $newUserId via sponsor $sponsorId");
        $this->calculateAction->execute($newUserId, $sponsorId);
    }
}

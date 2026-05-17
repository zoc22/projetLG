<?php

namespace Modules\Commission\Services;

use Modules\Commission\Models\Commission;
use Modules\Commission\Models\MatchingBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;
use Modules\Genealogy\Models\GenealogyNode;
use Illuminate\Support\Facades\DB;

/**
 * Calcul du Matching Bonus (50% des gains matriciels des directs).
 */
class MatchingBonusService
{
    public function __construct(protected CommissionService $commissionService) {}

    /**
     * Calcule le Matching Bonus pour un utilisateur basé sur ses directs.
     */
    public function calculateForUser(string $userId, string $periodString): void
    {
        // Récupérer les directs
        $directs = GenealogyNode::where('sponsor_id', $userId)->get();

        foreach ($directs as $direct) {
            // Récupérer le bonus matriciel du direct pour la même période
            $matrixCommission = Commission::where('user_id', $direct->user_id)
                ->where('period_string', $periodString)
                ->where('type', CommissionTypeEnum::MONTHLY) // Supposons que MATRIX est MONTHLY
                ->first();

            if ($matrixCommission && $matrixCommission->amount > 0) {
                $matchingAmount = $matrixCommission->amount * 0.5;

                $dto = new BonusCalculationDTO(
                    userId: $userId,
                    amount: $matchingAmount,
                    bonusType: BonusTypeEnum::MATCHING,
                    periodString: $periodString,
                    sourceId: $direct->user_id
                );

                $commission = $this->commissionService->storeCommission($dto, CommissionTypeEnum::MONTHLY);

                MatchingBonus::create([
                    'commission_id' => $commission->id,
                    'downline_id'   => $direct->user_id,
                    'generation'    => 1, // Direct
                    'percentage'    => 50.00
                ]);
            }
        }
    }
}


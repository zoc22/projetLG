<?php

namespace Modules\Commission\Services;

use Modules\Commission\Models\Commission;
use Modules\Commission\Models\FastStartBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;
use Illuminate\Support\Facades\DB;

/**
 * Calcul du Fast Start (50% direct + profondeur selon rang).
 */
class CalculateFastStartBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $newMemberId, string $sponsorId): void
    {
        $enrollmentFee = config('commission.bonuses.fast_start.enrollment_fee', 49.95);
        
        // Simuler la récupération de l'upline (Généalogie)
        // Dans un vrai projet, on injecterait un service de Genealogy ici.
        $upline = $this->getMockUpline($sponsorId); 

        foreach ($upline as $level => $affiliate) {
            $rankPercent = $this->getPercentageForRank($affiliate['rank'], $level);

            if ($rankPercent > 0) {
                $bonusAmount = ($enrollmentFee * $rankPercent) / 100;

                $dto = new BonusCalculationDTO(
                    userId: $affiliate['id'],
                    amount: $bonusAmount,
                    bonusType: BonusTypeEnum::FAST_START,
                    periodString: now()->format('Y-\WW'), // Semaine actuelle
                    sourceId: $newMemberId
                );

                $commission = $this->service->storeCommission($dto, CommissionTypeEnum::WEEKLY);

                FastStartBonus::create([
                    'commission_id' => $commission->id,
                    'downline_id'   => $newMemberId,
                    'level'         => $level,
                    'percentage'    => $rankPercent
                ]);
            }
        }
    }

    private function getPercentageForRank(string $rank, int $level): float
    {
        $levelsConfig = config('commission.bonuses.fast_start.levels');
        return $levelsConfig[$rank][$level - 1] ?? 0;
    }

    private function getMockUpline(string $sponsorId) {
        // Retourne une liste chainée d'affiliés avec leurs rangs
        return [
            1 => ['id' => $sponsorId, 'rank' => 'BRONZE'],
            2 => ['id' => 'grand-sponsor-id', 'rank' => 'GOLD'],
        ];
    }
}

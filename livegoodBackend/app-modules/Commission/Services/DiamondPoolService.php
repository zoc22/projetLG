<?php

namespace Modules\Commission\Services;

use Modules\Commission\Models\Commission;
use Modules\Commission\Models\DiamondPool;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;
use Modules\Genealogy\Models\GenealogyNode;
use Illuminate\Support\Facades\DB;

/**
 * Bonus Diamond Pool (2% du CA global partagé).
 */
class DiamondPoolService
{
    public function __construct(protected CommissionService $commissionService) {}

    /**
     * Calcule et distribue le Diamond Pool pour un mois donné.
     */
    public function distributePool(float $globalRevenue, string $periodString): void
    {
        $poolAmount = $globalRevenue * 0.02;
        
        $diamonds = GenealogyNode::where('rank', 'DIAMOND')->get();
        
        if ($diamonds->count() > 0) {
            $amountPerDiamond = $poolAmount / $diamonds->count();

            foreach ($diamonds as $diamond) {
                $dto = new BonusCalculationDTO(
                    userId: $diamond->user_id,
                    amount: $amountPerDiamond,
                    bonusType: BonusTypeEnum::DIAMOND_POOL,
                    periodString: $periodString,
                    sourceId: 'global_revenue'
                );

                $commission = $this->commissionService->storeCommission($dto, CommissionTypeEnum::MONTHLY);

                DiamondPool::create([
                    'commission_id'   => $commission->id,
                    'total_pool_size' => $poolAmount,
                    'share_amount'    => $amountPerDiamond
                ]);
            }
        }
    }
}


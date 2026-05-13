<?php

namespace Modules\Commission\Actions;

use Modules\Commission\Services\CommissionService;
use Modules\Commission\Models\DiamondPool;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

/**
 * Calcul du Diamond Pool Bonus.
 */
class CalculateDiamondPoolBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $userId, float $totalRevenue, int $totalDiamonds, string $period): void
    {
        $pool = ($totalRevenue * config('commission.bonuses.diamond_pool.company_revenue_percent', 2.00)) / 100;
        $amount = $pool / $totalDiamonds;

        if ($amount > 0) {
            $dto = new BonusCalculationDTO(
                userId: $userId,
                amount: $amount,
                bonusType: BonusTypeEnum::DIAMOND_POOL,
                periodString: $period
            );

            $commission = $this->service->storeCommission($dto, CommissionTypeEnum::MONTHLY);

            DiamondPool::create([
                'commission_id' => $commission->id,
                'total_company_revenue' => $totalRevenue,
                'eligible_diamonds_count' => $totalDiamonds
            ]);
        }
    }
}

<?php

namespace Modules\Commission\Actions;

use Modules\Commission\Services\CommissionService;
use Modules\Commission\Models\RetailBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

/**
 * Calcul du Retail Bonus (50% de la marge bénéficiaire).
 */
class CalculateRetailBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $userId, string $orderId, float $priceDiff, string $period): void
    {
        $percentage = config('commission.bonuses.retail.base_percent', 50.00);
        $amount = ($priceDiff * $percentage) / 100;

        if ($amount > 0) {
            $dto = new BonusCalculationDTO(
                userId: $userId,
                amount: $amount,
                bonusType: BonusTypeEnum::RETAIL,
                periodString: $period,
                sourceId: $orderId
            );

            $commission = $this->service->storeCommission($dto, CommissionTypeEnum::WEEKLY);

            RetailBonus::create([
                'commission_id' => $commission->id,
                'order_id' => $orderId,
                'price_difference' => $priceDiff
            ]);
        }
    }
}

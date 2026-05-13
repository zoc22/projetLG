<?php

namespace Modules\Commission\Actions;

use Modules\Commission\Services\CommissionService;
use Modules\Commission\Models\InfluencerBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

/**
 * Calcul de l'Influencer Bonus (Extra % sur volume de ventes).
 */
class CalculateInfluencerBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $userId, float $monthlyVolume, string $period): void
    {
        $extraPercent = $this->getExtraPercentage($monthlyVolume);

        if ($extraPercent > 0) {
            $amount = ($monthlyVolume * $extraPercent) / 100;

            $dto = new BonusCalculationDTO(
                userId: $userId,
                amount: $amount,
                bonusType: BonusTypeEnum::INFLUENCER,
                periodString: $period
            );

            $commission = $this->service->storeCommission($dto, CommissionTypeEnum::MONTHLY);

            InfluencerBonus::create([
                'commission_id' => $commission->id,
                'monthly_sales_volume' => $monthlyVolume,
                'extra_percentage' => $extraPercent
            ]);
        }
    }

    private function getExtraPercentage(float $volume): float
    {
        $thresholds = config('commission.bonuses.influencer.thresholds');
        $extra = 0;
        foreach ($thresholds as $t) {
            if ($volume >= $t['sales']) {
                $extra = $t['extra'];
            }
        }
        return $extra;
    }
}

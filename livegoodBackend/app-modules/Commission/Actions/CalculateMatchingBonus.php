<?php

namespace Modules\Commission\Actions;

use Modules\Commission\Services\CommissionService;
use Modules\Commission\Models\MatchingBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

/**
 * Calcul du Matching Bonus (50% direct + profondeur).
 */
class CalculateMatchingBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $userId, string $sourceAffiliateId, float $sourceAmount, string $period): void
    {
        // On récupère le pourcentage (ex: 50% pour un direct)
        $percentage = config('commission.bonuses.matching.direct_match_percent', 50.00);
        $amount = ($sourceAmount * $percentage) / 100;

        if ($amount > 0) {
            $dto = new BonusCalculationDTO(
                userId: $userId,
                amount: $amount,
                bonusType: BonusTypeEnum::MATCHING,
                periodString: $period,
                sourceId: $sourceAffiliateId
            );

            $commission = $this->service->storeCommission($dto, CommissionTypeEnum::MONTHLY);

            MatchingBonus::create([
                'commission_id' => $commission->id,
                'source_affiliate_id' => $sourceAffiliateId,
                'source_matrix_amount' => $sourceAmount,
                'match_percentage' => $percentage
            ]);
        }
    }
}

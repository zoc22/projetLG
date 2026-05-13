<?php

namespace Modules\Commission\Actions;

use Modules\Commission\Services\CommissionService;
use Modules\Commission\Models\MatrixBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

/**
 * Action spécifique pour calculer le bonus Powerline d'un utilisateur.
 */
class CalculateMatrixBonus
{
    public function __construct(protected CommissionService $service) {}

    public function execute(string $userId, int $activeCount, string $period): void
    {
        $rate = config('commission.bonuses.matrix.commission_per_member', 0.25);
        $amount = $activeCount * $rate;

        if ($amount > 0) {
            $dto = new BonusCalculationDTO(
                userId: $userId,
                amount: $amount,
                bonusType: BonusTypeEnum::MATRIX,
                periodString: $period
            );

            $commission = $this->service->storeCommission($dto, CommissionTypeEnum::MONTHLY);

            MatrixBonus::create([
                'commission_id' => $commission->id,
                'active_members_count' => $activeCount
            ]);
        }
    }
}

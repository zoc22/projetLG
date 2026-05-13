<?php

namespace Modules\Commission\Services;

use Modules\Commission\Models\Commission;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\CommissionStatusEnum;
use Modules\Commission\Enums\CommissionTypeEnum;
use Illuminate\Support\Facades\DB;

/**
 * Service central pour la persistence des commissions.
 */
class CommissionService
{
    /**
     * Enregistre une commission de manière générique.
     */
    public function storeCommission(BonusCalculationDTO $dto, CommissionTypeEnum $type): Commission
    {
        return DB::transaction(function () use ($dto, $type) {
            return Commission::create([
                'user_id'       => $dto->userId,
                'amount'        => $dto->amount,
                'status'        => CommissionStatusEnum::PENDING,
                'type'          => $type,
                'source'        => $dto->sourceId,
                'period_string' => $dto->periodString
            ]);
        });
    }

    /**
     * Valide toutes les commissions d'une période pour mise en paiement.
     */
    public function validatePeriodCommissions(string $periodString): void
    {
        Commission::where('period_string', $periodString)
            ->where('status', CommissionStatusEnum::PENDING)
            ->update(['status' => CommissionStatusEnum::VALIDATED]);
    }
}

<?php

namespace Modules\Payment\Services;

use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Models\PaymentMethod;
use Modules\Payment\Enums\WithdrawalStatusEnum;
use Illuminate\Support\Facades\DB;

/**
 * Service dédié à la gestion des demandes de retrait des affiliés.
 */
class WithdrawalService
{
    /**
     * Soumet une nouvelle demande de retrait.
     * Logique : Vérifie le solde (via Commission module) et applique les frais.
     */
    public function requestWithdrawal(string $userId, float $amount, string $methodId): WithdrawalRequest
    {
        return DB::transaction(function() use ($userId, $amount, $methodId) {
            $fees = config('payment.withdrawal.processing_fee', 1.00);
            
            return WithdrawalRequest::create([
                'user_id' => $userId,
                'payment_method_id' => $methodId,
                'amount' => $amount,
                'fees' => $fees,
                'net_amount' => $amount - $fees,
                'status' => WithdrawalStatusEnum::REQUESTED
            ]);
        });
    }

    /**
     * Approuve administrativement une demande.
     */
    public function approve(string $requestId): void
    {
        WithdrawalRequest::where('id', $requestId)->update([
            'status' => WithdrawalStatusEnum::APPROVED
        ]);
    }
}

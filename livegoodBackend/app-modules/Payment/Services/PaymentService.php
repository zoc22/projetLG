<?php

namespace Modules\Payment\Services;

use Modules\Payment\Models\Payment;
use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Enums\WithdrawalStatusEnum;
use Modules\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service orchestrant les opérations de paiement.
 */
class PaymentService
{
    /**
     * Enregistre un nouveau paiement dans le système.
     */
    public function createPayment(array $data): Payment
    {
        return Payment::create($data);
    }

    /**
     * Met à jour le statut d'un paiement suite à un retour gateway.
     */
    public function updatePaymentStatus(string $paymentId, PaymentStatusEnum $status, ?string $reference = null): void
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->update([
            'status' => $status,
            'reference' => $reference ?? $payment->reference
        ]);
        
        // Déclencher les évènements correspondants
    }
}

<?php

namespace Modules\Payment\Services;

use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Gateways\PaymentGatewayInterface;
use Modules\Payment\Enums\WithdrawalStatusEnum;
use Modules\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\Log;

/**
 * Service gérant l'exécution réelle des paiements vers l'extérieur.
 */
class PayoutService
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Traite un retrait unique via la gateway appropriée.
     */
    public function processSingleWithdrawal(WithdrawalRequest $request): void
    {
        try {
            $method = $request->method; // Relation vers PaymentMethod
            $gateway = $this->resolveGateway($method->type);

            $result = $gateway->transfer(
                $request->net_amount, 
                'USD', 
                $method->details
            );

            // Marquer comme envoyé
            $request->update([
                'status' => WithdrawalStatusEnum::SENT,
                'processed_at' => now()
            ]);

            // Créer la transaction correspondante
            $this->paymentService->createPayment([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'method' => $method->type,
                'status' => PaymentStatusEnum::COMPLETED,
                'reference' => $result['reference'] ?? null,
                'description' => "Payout withdrawal #{$request->id}"
            ]);

        } catch (\Exception $e) {
            Log::error("Échec du payout #{$request->id}: " . $e->getMessage());
            // Logique de retry ou notification admin
        }
    }

    /**
     * Usine à Gateways simple.
     */
    protected function resolveGateway(string $type): PaymentGatewayInterface
    {
        return match ($type) {
            'crypto' => app(\Modules\Payment\Gateways\CryptoGateway::class),
            'bank_wire' => app(\Modules\Payment\Gateways\BankTransferGateway::class),
            default => throw new \Exception("Gateway non supportée : $type"),
        };
    }
}

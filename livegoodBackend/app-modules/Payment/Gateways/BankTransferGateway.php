<?php

namespace Modules\Payment\Gateways;

/**
 * Implémentation fictive pour les transferts bancaires.
 */
class BankTransferGateway implements PaymentGatewayInterface
{
    public function transfer(float $amount, string $currency, array $details): array
    {
        // Simuler appel API bancaire (Swift/Sepa)
        return [
            'success' => true,
            'reference' => 'BANK-' . uniqid()
        ];
    }

    public function verifyStatus(string $transactionId): string
    {
        return 'completed';
    }
}

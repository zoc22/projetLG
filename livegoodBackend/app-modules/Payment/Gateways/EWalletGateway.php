<?php

namespace Modules\Payment\Gateways;

/**
 * Gateway pour Global Payout / E-Wallet.
 */
class EWalletGateway implements PaymentGatewayInterface
{
    public function transfer(float $amount, string $currency, array $details): array
    {
        return [
            'success' => true,
            'reference' => 'EWAL-' . uniqid()
        ];
    }

    public function verifyStatus(string $transactionId): string
    {
        return 'completed';
    }
}

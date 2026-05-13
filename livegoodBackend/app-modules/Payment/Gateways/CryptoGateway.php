<?php

namespace Modules\Payment\Gateways;

/**
 * Gateway pour les paiements en Cryptomonnaies.
 */
class CryptoGateway implements PaymentGatewayInterface
{
    public function transfer(float $amount, string $currency, array $details): array
    {
        // Simulation d'envoi vers un Wallet (ex: BitPay, CoinPayments)
        return [
            'success' => true,
            'reference' => 'CRYPTO-' . bin2hex(random_bytes(8))
        ];
    }

    public function verifyStatus(string $transactionId): string
    {
        return 'completed';
    }
}

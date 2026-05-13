<?php

namespace Modules\Payment\Gateways;

/**
 * Interface standard pour tous les processeurs de paiement.
 */
interface PaymentGatewayInterface
{
    /**
     * Initie un transfert de fonds vers un tiers (Payout).
     */
    public function transfer(float $amount, string $currency, array $details): array;

    /**
     * Vérifie le statut d'une transaction externe.
     */
    public function verifyStatus(string $transactionId): string;
}

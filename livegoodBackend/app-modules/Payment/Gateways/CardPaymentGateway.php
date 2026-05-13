<?php

namespace Modules\Payment\Gateways;

/**
 * Gateway pour les paiements par Carte Bancaire (ex: Stripe, Authorize.net).
 * Principalement utilisé pour les achats d'abonnements par les membres.
 */
class CardPaymentGateway implements PaymentGatewayInterface
{
    public function transfer(float $amount, string $currency, array $details): array
    {
        // Les gateways par carte ne sont généralement pas utilisés pour les payouts 
        // (retrait d'argent), mais on implémente l'interface par cohérence.
        throw new \Exception("Les retraits par carte ne sont pas supportés nativement.");
    }

    public function charge(float $amount, string $token): array
    {
        // Simulation d'un débit sur une carte
        return [
            'success' => true,
            'transaction_id' => 'CARD-' . uniqid()
        ];
    }

    public function verifyStatus(string $transactionId): string
    {
        return 'completed';
    }
}

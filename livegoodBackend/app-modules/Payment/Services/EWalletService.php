<?php

namespace Modules\Payment\Services;

/**
 * Logique spécifique à Global Payout ou autre E-Wallet provider.
 */
class EWalletService
{
    public function checkAccountExists(string $email): bool
    {
        // Simulation d'une vérification via API externe
        return true;
    }
}

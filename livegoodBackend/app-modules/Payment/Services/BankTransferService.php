<?php

namespace Modules\Payment\Services;

/**
 * Logique métier pour les virements bancaires SWIFT/SEPA.
 */
class BankTransferService
{
    public function validateIBAN(string $iban): bool
    {
        // Simulation validation IBAN
        return strlen($iban) > 15;
    }
}

<?php

namespace Modules\Payment\Services;

/**
 * Service spécifique aux cryptomonnaies.
 */
class CryptoService
{
    /**
     * Valide une adresse de wallet crypto avant enregistrement.
     */
    public function validateAddress(string $address, string $network): bool
    {
        return strlen($address) >= 26; // Simulation basique
    }
}

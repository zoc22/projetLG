<?php

namespace Modules\Payment\Enums;

/**
 * Moyens de paiement supportés par la plateforme.
 * Ref: Diagramme de classe (ModePaiement)
 */
enum PaymentMethodEnum: string
{
    case CREDIT_CARD = 'credit_card';
    case CRYPTO      = 'crypto';
    case EWALLET     = 'e_wallet';
    case BANK_WIRE   = 'bank_wire';
    case MOMO_CMR    = 'momo_cmr';
}

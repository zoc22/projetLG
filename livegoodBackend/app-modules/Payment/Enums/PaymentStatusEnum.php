<?php

namespace Modules\Payment\Enums;

/**
 * Statuts des transactions de paiement.
 * Ref: Diagramme de classe (StatutPaiement)
 */
enum PaymentStatusEnum: string
{
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case FAILED     = 'failed';
    case REFUNDED   = 'refunded';
}

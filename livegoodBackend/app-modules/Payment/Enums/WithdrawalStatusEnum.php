<?php

namespace Modules\Payment\Enums;

/**
 * Statuts des demandes de retrait d'argent (Withdrawal).
 */
enum WithdrawalStatusEnum: string
{
    case REQUESTED = 'requested';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case SENT      = 'sent';
}

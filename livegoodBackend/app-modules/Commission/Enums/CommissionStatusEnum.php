<?php

namespace Modules\Commission\Enums;

enum CommissionStatusEnum: string
{
    case PENDING    = 'pending';    // Calculée mais pas encore validée
    case VALIDATED  = 'validated';  // Prête pour le paiement
    case PAID       = 'paid';       // Payée à l'affilié
    case CANCELLED  = 'cancelled';  // Annulée (ex: remboursement produit)
}

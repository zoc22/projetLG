<?php

namespace Modules\Affiliation\Enums;

/**
 * Statuts d'affiliation au sein de LiveGood.
 */
enum AffiliationStatusEnum: string
{
    case PENDING   = 'pending';    // En attente de paiement final
    case ACTIVE    = 'active';     // Membre et Affilié avec abonnement à jour
    case INACTIVE  = 'inactive';   // Abonnement expiré
    case TERMINATED = 'terminated'; // Compte clôturé
}

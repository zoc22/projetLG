<?php

namespace Modules\Marketing\Enums;

/**
 * Statuts possibles pour une pré-inscription (Lead).
 */
enum LeadStatusEnum: string
{
    case ACTIVE    = 'active';    // Pré-inscrit, temps restant avant jeudi minuit
    case CONVERTED = 'converted'; // Devenu membre payant
    case EXPIRED   = 'expired';   // N'a pas validé à temps
}

<?php

namespace Modules\Statistics\Enums;

/**
 * Types de métriques supportées pour les agrégations.
 */
enum MetricTypeEnum: string
{
    case VISITS          = 'visits';           // Nombre total de clics
    case PREINSCRIPTIONS = 'preinscriptions';   // Leads (Pre-enrolled)
    case CONVERSIONS     = 'conversions';      // Inscriptions payées
    case REVENUE         = 'revenue';          // Chiffre d'affaires
}

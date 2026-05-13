<?php

namespace Modules\Commission\Services;

/**
 * Répartition des 2% des ventes globales aux Diamonds.
 */
class DiamondPoolService
{
    public function __construct(protected CommissionService $commissionService) {}
}

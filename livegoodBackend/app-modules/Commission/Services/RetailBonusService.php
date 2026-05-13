<?php

namespace Modules\Commission\Services;

/**
 * Bonus sur les ventes au détail (différence de prix).
 */
class RetailBonusService
{
    public function __construct(protected CommissionService $commissionService) {}
}

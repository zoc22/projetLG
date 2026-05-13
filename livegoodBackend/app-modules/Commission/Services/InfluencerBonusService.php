<?php

namespace Modules\Commission\Services;

/**
 * Bonus supplémentaire pour gros volumes de ventes mensuelles.
 */
class InfluencerBonusService
{
    public function __construct(protected CommissionService $commissionService) {}
}

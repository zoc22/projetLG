<?php

namespace Modules\Commission\Services;

/**
 * Calcul du Matching Bonus (50% des gains matriciels des directs).
 */
class MatchingBonusService
{
    public function __construct(protected CommissionService $commissionService) {}
}

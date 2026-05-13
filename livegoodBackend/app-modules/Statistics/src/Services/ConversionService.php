<?php

namespace Modules\Statistics\Services;

/**
 * Service pour la conversion de leads en membres.
 */
class ConversionService
{
    public function __construct(protected VisitTrackerService $tracker) {}

    /**
     * Enregistre une nouvelle pré-inscription dans les stats.
     */
    public function recordLead(string $affiliateId): void
    {
        $this->tracker->incrementDailyCounter($affiliateId, 'preinscriptions_count');
    }

    /**
     * Enregistre une conversion finale (paiement effectué).
     */
    public function recordSale(string $affiliateId, float $amount): void
    {
        $this->tracker->incrementDailyCounter($affiliateId, 'conversions_count');
        $this->tracker->incrementDailyCounter($affiliateId, 'revenue_amount', (int)$amount);
    }
}

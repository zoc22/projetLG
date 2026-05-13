<?php

namespace Modules\Statistics\Services;

use Modules\Statistics\Models\SiteVisit;
use Modules\Statistics\Models\DailyAggregation;
use Modules\Statistics\Enums\VisitSourceEnum;
use Illuminate\Support\Facades\Request;

/**
 * Service pour tracer les interactions entrantes (clics).
 */
class VisitTrackerService
{
    /**
     * Enregistre une nouvelle visite.
     * Cette méthode doit être appelée sur les gateways de capture ou sites de retail.
     */
    public function trackVisit(string $affiliateId, string $siteType, array $metadata = []): SiteVisit
    {
        $visit = SiteVisit::create([
            'affiliate_id' => $affiliateId,
            'site_type'    => $siteType,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
            'source'       => $metadata['source'] ?? VisitSourceEnum::DIRECT->value,
            'country_code' => $metadata['country'] ?? null,
        ]);

        // Incrémentation atomique du cache quotidien pour éviter de relancer un agrégat lourd
        $this->incrementDailyCounter($affiliateId, 'visits_count');

        return $visit;
    }

    /**
     * Incrémenteur rapide de cache statistique.
     */
    public function incrementDailyCounter(string $affiliateId, string $field, int $value = 1): void
    {
        DailyAggregation::updateOrCreate(
            ['affiliate_id' => $affiliateId, 'reference_date' => now()->toDateString()],
            [$field => DB::raw("$field + $value")]
        );
    }
}

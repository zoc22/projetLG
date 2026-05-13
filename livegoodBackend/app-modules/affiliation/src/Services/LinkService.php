<?php

namespace Modules\Affiliation\Services;

use Modules\Affiliation\Models\AffiliateLink;

/**
 * Service pour la gestion technique des liens.
 */
class LinkService
{
    public function trackClick(string $slug): void
    {
        AffiliateLink::where('slug', $slug)->increment('clicks_count');
    }

    public function getLinksForAffiliate(string $affiliateId)
    {
        return AffiliateLink::where('affiliate_id', $affiliateId)->get();
    }
}

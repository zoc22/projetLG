<?php

namespace Modules\Affiliation\Actions;

use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Models\AffiliateLink;
use Illuminate\Support\Str;

/**
 * Génère un nouveau lien tracké pour l'affilié.
 */
class GenerateAffiliateLink
{
    public function execute(string $affiliateId, string $targetType, string $name): AffiliateLink
    {
        $affiliate = Affiliate::findOrFail($affiliateId);
        
        return AffiliateLink::create([
            'affiliate_id' => $affiliateId,
            'name'         => $name,
            'target_type'  => $targetType,
            'slug'         => $affiliate->username_canonical . '-' . Str::random(5)
        ]);
    }
}

<?php

namespace Modules\Affiliation\Observers;

use Modules\Affiliation\Models\Affiliate;
use Illuminate\Support\Facades\Log;

/**
 * Observe les changements sur le modèle Affiliate pour déclencher des effets de bord.
 */
class AffiliateObserver
{
    public function updated(Affiliate $affiliate): void
    {
        if ($affiliate->wasChanged('rank')) {
            Log::info("Affiliate {$affiliate->id} reached rank {$affiliate->rank->value}");
        }
    }
}

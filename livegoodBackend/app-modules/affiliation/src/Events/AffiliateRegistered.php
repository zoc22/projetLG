<?php

namespace Modules\Affiliation\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Affiliation\Models\Affiliate;

class AffiliateRegistered
{
    use SerializesModels;
    public function __construct(public Affiliate $affiliate) {}
}

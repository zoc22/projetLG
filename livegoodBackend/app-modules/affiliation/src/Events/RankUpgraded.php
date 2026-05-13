<?php

namespace Modules\Affiliation\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Enums\RankEnum;

class RankUpgraded
{
    use SerializesModels;
    public function __construct(public Affiliate $affiliate, public RankEnum $newRank) {}
}

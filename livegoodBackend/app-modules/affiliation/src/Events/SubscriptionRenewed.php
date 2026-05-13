<?php

namespace Modules\Affiliation\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Affiliation\Models\Subscription;

class SubscriptionRenewed
{
    use SerializesModels;
    public function __construct(public Subscription $subscription) {}
}

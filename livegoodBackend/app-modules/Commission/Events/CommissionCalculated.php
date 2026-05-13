<?php

namespace Modules\Commission\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Commission\Models\Commission;

class CommissionCalculated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Commission $commission) {}
}

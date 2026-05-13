<?php

namespace Modules\Payment\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\WithdrawalRequest;

class WithdrawalRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(public WithdrawalRequest $request) {}
}

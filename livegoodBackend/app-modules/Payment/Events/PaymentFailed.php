<?php

namespace Modules\Payment\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\Payment;

class PaymentFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(public Payment $payment, public string $reason) {}
}

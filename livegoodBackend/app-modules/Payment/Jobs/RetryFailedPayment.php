<?php

namespace Modules\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\Payment;
use Modules\Payment\Services\PaymentService;

/**
 * Job de relance pour les paiements échoués (cas d'erreur réseau transitoire).
 */
class RetryFailedPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $paymentId) {}

    public function handle(PaymentService $service): void
    {
        $payment = Payment::find($this->paymentId);
        if ($payment && $payment->status === \Modules\Payment\Enums\PaymentStatusEnum::FAILED) {
            // Logique de retry vers la gateway
        }
    }
}

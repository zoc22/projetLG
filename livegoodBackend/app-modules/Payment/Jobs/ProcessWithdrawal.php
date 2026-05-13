<?php

namespace Modules\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Services\PayoutService;

/**
 * Traitement d'un retrait spécifique (ex: retrait Express ou instantané).
 */
class ProcessWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $withdrawalId) {}

    public function handle(PayoutService $service): void
    {
        $request = WithdrawalRequest::find($this->withdrawalId);
        if ($request) {
            $service->processSingleWithdrawal($request);
        }
    }
}

<?php

namespace Modules\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Enums\WithdrawalStatusEnum;
use Modules\Payment\Services\PayoutService;

/**
 * Job pour traiter les paiements en masse (Payouts hebdomadaires).
 */
class ProcessWeeklyPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(PayoutService $payoutService): void
    {
        // Récupérer toutes les demandes approuvées non encore envoyées
        $requests = WithdrawalRequest::where('status', WithdrawalStatusEnum::APPROVED)->get();

        foreach ($requests as $request) {
            $payoutService->processSingleWithdrawal($request);
        }
    }
}

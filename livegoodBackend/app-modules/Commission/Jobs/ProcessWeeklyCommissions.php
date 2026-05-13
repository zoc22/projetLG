<?php

namespace Modules\Commission\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Commission\Services\CommissionService;

class ProcessWeeklyCommissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(CommissionService $service): void
    {
        // Valider les commissions de la semaine passée
        $period = now()->subWeek()->format('Y-\WW');
        $service->validatePeriodCommissions($period);
    }
}

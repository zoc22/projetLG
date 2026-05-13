<?php

namespace Modules\Commission\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Commission\Services\DiamondPoolService;

class ProcessDiamondPool implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(DiamondPoolService $service): void
    {
        // Calculer les 2% et distribuer
    }
}

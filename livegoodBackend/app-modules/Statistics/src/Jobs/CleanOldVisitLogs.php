<?php

namespace Modules\Statistics\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Statistics\Models\SiteVisit;
use Illuminate\Support\Facades\Config;

/**
 * Job de maintenance pour nettoyer les millions de logs de visites brutes.
 */
class CleanOldVisitLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $retention = Config::get('statistics.retention_days', 90);
        
        SiteVisit::where('created_at', '<', now()->subDays($retention))
            ->delete();
    }
}

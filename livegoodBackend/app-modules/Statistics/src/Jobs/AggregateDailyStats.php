<?php

namespace Modules\Statistics\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Statistics\Models\SiteVisit;
use Modules\Statistics\Models\DailyAggregation;
use Illuminate\Support\Facades\DB;

/**
 * Job d'agrégation massive (facultatif si l'on utilise les compteurs atomiques,
 * mais utile pour le rattrapage en cas de bug logic de compteur).
 */
class AggregateDailyStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Re-calculer les agrégats de la veille pour assurer la cohérence
        $yesterday = now()->subDay()->toDateString();

        $stats = SiteVisit::select('affiliate_id', DB::raw('count(*) as total'))
            ->whereDate('created_at', $yesterday)
            ->groupBy('affiliate_id')
            ->get();

        foreach ($stats as $row) {
            DailyAggregation::updateOrCreate(
                ['affiliate_id' => $row->affiliate_id, 'reference_date' => $yesterday],
                ['visits_count' => $row->total]
            );
        }
    }
}

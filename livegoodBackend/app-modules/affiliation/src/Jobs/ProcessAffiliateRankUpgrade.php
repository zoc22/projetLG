<?php

namespace Modules\Affiliation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Services\RankService;

/**
 * Job de recalcul de rang en tâche de fond.
 */
class ProcessAffiliateRankUpgrade implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $affiliateId) {}

    public function handle(RankService $rankService): void
    {
        $rankService->evaluateQualification($this->affiliateId);
    }
}

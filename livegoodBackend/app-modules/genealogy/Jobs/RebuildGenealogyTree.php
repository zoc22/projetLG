<?php

namespace Modules\Genealogy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Genealogy\Models\GenealogyNode;

/**
 * Job de secours pour recalculer les Materialized Paths en cas d'incohérence.
 */
class RebuildGenealogyTree implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $roots = GenealogyNode::whereNull('sponsor_id')->get();

        foreach ($roots as $root) {
            $this->rebuild($root, $root->user_id);
        }
    }

    private function rebuild(GenealogyNode $node, string $path): void
    {
        $node->update(['path' => $path]);

        foreach ($node->referrals as $child) {
            $this->rebuild($child, $path . '.' . $child->user_id);
        }
    }
}

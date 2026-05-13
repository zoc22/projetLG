<?php

namespace Modules\Genealogy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Genealogy\Models\GenealogyNode;
use Modules\Genealogy\Actions\CalculateMatrix;

/**
 * Job planifié pour mettre à jour les bonus matriciels de tous les membres actifs.
 */
class ProcessWeeklyMatrixUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Exécute le calcul pour chaque membre actif.
     */
    public function handle(CalculateMatrix $action): void
    {
        // Traitement par lots pour optimiser les performances de la base de données
        GenealogyNode::where('is_active', true)->chunk(100, function ($nodes) use ($action) {
            foreach ($nodes as $node) {
                $action->execute($node->user_id);
            }
        });
    }
}

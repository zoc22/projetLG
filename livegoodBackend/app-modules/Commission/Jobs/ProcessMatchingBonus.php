<?php

namespace Modules\Commission\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Commission\Actions\CalculateMatchingBonus;
use Modules\Commission\Models\Commission;
use Modules\Commission\Enums\BonusTypeEnum;

class ProcessMatchingBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $periodString) {}

    public function handle(CalculateMatchingBonus $action): void
    {
        // Récupérer toutes les commissions matricielles de la période
        $matrixCommissions = Commission::with('user')
            ->where('period_string', $this->periodString)
            ->whereHas('matrix')
            ->get();

        foreach ($matrixCommissions as $commission) {
            $user = $commission->user;
            $sponsorId = $user->sponsor_id; // Supposé être dans la table users ou genealogy_nodes

            if ($sponsorId) {
                // Calcul du match 50% pour le parrain direct
                $action->execute($sponsorId, $user->id, $commission->amount, $this->periodString);
                
                // Ici on pourrait aussi déclencher le calcul pour les générations (Silver+)
            }
        }
    }
}

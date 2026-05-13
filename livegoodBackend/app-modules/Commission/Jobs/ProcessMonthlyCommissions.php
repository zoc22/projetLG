<?php

namespace Modules\Commission\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Commission\Actions\CalculateMatrixBonus;
use Modules\Commission\Actions\CalculateMatchingBonus;
use App\Models\User;

class ProcessMonthlyCommissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(CalculateMatrixBonus $matrixAction, CalculateMatchingBonus $matchingAction): void
    {
        $period = now()->subMonth()->format('Y-m');

        // 1. Calculer la matrice pour tout le monde
        User::where('is_active', true)->chunk(100, function($users) use ($matrixAction, $period) {
            foreach($users as $user) {
                // Simuler count membres actifs sous lui
                $count = 100; 
                $matrixAction->execute($user->id, $count, $period);
            }
        });

        // 2. Calculer le matching bonus basé sur les résultats de la matrice
        // ... Logique d'itération upline
    }
}

<?php

namespace Modules\Statistics\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Statistics\Models\DailyAggregation;
use App\Models\User;

/**
 * Génère des données de test pour les graphiques.
 */
class StatisticsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(3)->get();

        foreach ($users as $user) {
            for ($i = 0; $i < 30; $i++) {
                DailyAggregation::create([
                    'affiliate_id'   => $user->id,
                    'reference_date' => now()->subDays($i)->toDateString(),
                    'visits_count'   => rand(50, 500),
                    'preinscriptions_count' => rand(5, 50),
                    'conversions_count' => rand(1, 5),
                    'revenue_amount' => rand(100, 1000) / 10
                ]);
            }
        }
    }
}

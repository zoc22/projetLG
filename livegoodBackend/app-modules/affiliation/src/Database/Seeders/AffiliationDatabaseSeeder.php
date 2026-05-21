<?php

namespace Modules\Affiliation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Affiliation\Models\Affiliate;
use Modules\Authentication\Models\User;

class AffiliationDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer quelques affiliés de démonstration
        User::whereIn('type_utilisateur', ['affiliate', 'super_admin'])->get()->each(function ($user) {
            Affiliate::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'username_canonical' => strtolower($user->prenom . $user->nom),
                    'code_affiliation' => $user->id, // Use UUID as code for tests if needed, or random
                    'status' => 'active',
                    'rank' => 'unranked'
                ]
            );
        });
    }
}

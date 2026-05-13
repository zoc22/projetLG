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
        User::where('role', 'affiliate')->get()->each(function ($user) {
            Affiliate::create([
                'user_id' => $user->id,
                'username_canonical' => $user->username ?? 'user-' . $user->id,
                'referral_code' => strtoupper(\Illuminate\Support\Str::random(10)),
                'status' => 'active',
                'rank' => 'unranked'
            ]);
        });
    }
}

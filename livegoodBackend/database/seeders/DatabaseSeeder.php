<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Modules\Authentication\Database\Seeders\AuthDatabaseSeeder::class,
            \Modules\Affiliation\Database\Seeders\AffiliationDatabaseSeeder::class,
        ]);
    }
}

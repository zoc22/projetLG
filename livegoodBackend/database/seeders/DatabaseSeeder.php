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
        User::create([
            'id' => Str::uuid(),
            'nom' => 'Admin',
            'prenom' => 'LiveGood',
            'email' => 'admin@livegood.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'statut_compte' => 'actif',
            'type_utilisateur' => 'super_admin',
        ]);
        
        echo "User admin@livegood.com created with password: password\n";
    }
}

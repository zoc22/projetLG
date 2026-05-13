<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\PaymentMethod;
use App\Models\User;

class PaymentDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des méthodes de test pour les utilisateurs existants
        $users = User::limit(5)->get();
        foreach ($users as $user) {
            PaymentMethod::create([
                'user_id' => $user->id,
                'type' => \Modules\Payment\Enums\PaymentMethodEnum::CRYPTO,
                'details' => ['address' => 'bc1q' . bin2hex(random_bytes(10))],
                'is_default' => true
            ]);
        }
    }
}

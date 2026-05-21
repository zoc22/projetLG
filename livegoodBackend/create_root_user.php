<?php

use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Models\User;
use Modules\Authentication\Enums\UserStatusEnum;
use Modules\Authentication\Enums\UserRoleEnum;

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $email = 'admin@livegood.test';
    
    if (User::where('email', $email)->exists()) {
        echo "L'utilisateur admin existe déjà.\n";
        exit;
    }

    $user = User::create([
        'nom' => 'System',
        'prenom' => 'Admin',
        'email' => $email,
        'password' => Hash::make('Password123!'),
        'statut_compte' => 'actif',
        'type_utilisateur' => 'super_admin',
        'pays' => 'France',
        'devise' => 'USD'
    ]);

    echo "Utilisateur ROOT créé avec succès : \n";
    echo "Email: admin@livegood.test\n";
    echo "Pass: Password123!\n";
    echo "ID: " . $user->id . "\n";

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

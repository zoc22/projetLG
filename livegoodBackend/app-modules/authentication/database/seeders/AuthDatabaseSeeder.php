<?php
declare(strict_types=1);

namespace Modules\Authentication\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Models\User;
use Modules\Authentication\Enums\UserRoleEnum;
use Modules\Authentication\Enums\UserStatusEnum;

/**
 * Seeder AuthDatabaseSeeder
 *
 * Peuple la base de données avec des utilisateurs par défaut
 * pour le développement et les tests.
 *
 * @package Modules\Authentication\Database\Seeders
 */
class AuthDatabaseSeeder extends Seeder
{
    /**
     * Exécute le seeder
     *
     * @return void
     */
    public function run(): void
    {
        // Créer un super administrateur
        User::updateOrCreate(
            ['email' => 'admin@livegood.com'],
            [
                'nom' => 'Administrator',
                'prenom' => 'Super',
                'password' => Hash::make('Admin123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'type_utilisateur' => UserRoleEnum::SUPER_ADMIN->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
            ]
        );

        // Créer un administrateur standard
        User::updateOrCreate(
            ['email' => 'admin2@livegood.com'],
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'password' => Hash::make('Admin123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'type_utilisateur' => UserRoleEnum::ADMIN->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
            ]
        );

        // Créer un support client
        User::updateOrCreate(
            ['email' => 'support@livegood.com'],
            [
                'nom' => 'Support',
                'prenom' => 'Client',
                'password' => Hash::make('Support123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'type_utilisateur' => UserRoleEnum::SUPPORT->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
            ]
        );

        // Créer un affilié test
        User::updateOrCreate(
            ['email' => 'affiliate@livegood.com'],
            [
                'nom' => 'Affiliate',
                'prenom' => 'Test',
                'password' => Hash::make('Affiliate123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'type_utilisateur' => UserRoleEnum::AFFILIATE->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
            ]
        );

        // Créer un membre test
        User::updateOrCreate(
            ['email' => 'member@livegood.com'],
            [
                'nom' => 'Member',
                'prenom' => 'Test',
                'password' => Hash::make('Member123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'type_utilisateur' => UserRoleEnum::MEMBER->value,
                'email_verified_at' => now(),
                'date_inscription' => now(),
            ]
        );

        // Créer un compte en attente de vérification
        User::updateOrCreate(
            ['email' => 'pending@livegood.com'],
            [
                'nom' => 'Pending',
                'prenom' => 'User',
                'password' => Hash::make('Pending123!'),
                'pays' => 'FR',
                'devise' => 'EUR',
                'statut_compte' => UserStatusEnum::EN_ATTENTE_VERIFICATION->value,
                'type_utilisateur' => UserRoleEnum::MEMBER->value,
                'email_verified_at' => null,
                'date_inscription' => now(),
            ]
        );

        $this->command->info('✅ Utilisateurs de test créés avec succès !');
        $this->command->info('📧 admin@livegood.com / Admin123!');
        $this->command->info('📧 affiliate@livegood.com / Affiliate123!');
        $this->command->info('📧 member@livegood.com / Member123!');
    }
}

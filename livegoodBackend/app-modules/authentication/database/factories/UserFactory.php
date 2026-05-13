<?php
declare(strict_types=1);

namespace Modules\Authentication\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Models\User;
use Modules\Authentication\Enums\UserRoleEnum;
use Modules\Authentication\Enums\UserStatusEnum;

/**
 * Factory UserFactory
 *
 * Génère des données de test pour les utilisateurs.
 *
 * @package Modules\Authentication\Database\Factories
 */
class UserFactory extends Factory
{
    /**
     * Le modèle associé à la factory
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Définit la configuration par défaut
     *
     * @return array
     */
    public function definition(): array
    {
        $statuses = [
            UserStatusEnum::ACTIF->value,
            UserStatusEnum::INACTIF->value,
            UserStatusEnum::SUSPENDU->value,
            UserStatusEnum::BLOQUE->value,
            UserStatusEnum::EN_ATTENTE_VERIFICATION->value,
        ];

        $roles = [
            UserRoleEnum::SUPER_ADMIN->value,
            UserRoleEnum::ADMIN->value,
            UserRoleEnum::SUPPORT->value,
            UserRoleEnum::AFFILIATE->value,
            UserRoleEnum::MEMBER->value,
        ];

        return [
            'id' => $this->faker->uuid(),
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'pays' => $this->faker->country(),
            'devise' => $this->faker->randomElement(['USD', 'EUR', 'GBP', 'CAD']),
            'statut_compte' => $this->faker->randomElement($statuses),
            'type_utilisateur' => $this->faker->randomElement($roles),
            'email_verified_at' => $this->faker->optional(0.7)->dateTime(),
            'date_inscription' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ip_inscription' => $this->faker->ipv4(),
            'last_login_at' => $this->faker->optional(0.5)->dateTime(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configurer l'utilisateur comme super administrateur
     *
     * @return self
     */
    public function superAdmin(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type_utilisateur' => UserRoleEnum::SUPER_ADMIN->value,
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'email_verified_at' => now(),
            ];
        });
    }

    /**
     * Configurer l'utilisateur comme administrateur
     *
     * @return self
     */
    public function admin(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type_utilisateur' => UserRoleEnum::ADMIN->value,
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'email_verified_at' => now(),
            ];
        });
    }

    /**
     * Configurer l'utilisateur comme affilié
     *
     * @return self
     */
    public function affiliate(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type_utilisateur' => UserRoleEnum::AFFILIATE->value,
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'email_verified_at' => now(),
            ];
        });
    }

    /**
     * Configurer l'utilisateur comme membre
     *
     * @return self
     */
    public function member(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type_utilisateur' => UserRoleEnum::MEMBER->value,
                'statut_compte' => UserStatusEnum::ACTIF->value,
                'email_verified_at' => now(),
            ];
        });
    }

    /**
     * Configurer l'utilisateur avec email non vérifié
     *
     * @return self
     */
    public function unverified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
                'statut_compte' => UserStatusEnum::EN_ATTENTE_VERIFICATION->value,
            ];
        });
    }

    /**
     * Configurer l'utilisateur avec compte bloqué
     *
     * @return self
     */
    public function blocked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'statut_compte' => UserStatusEnum::BLOQUE->value,
            ];
        });
    }
}
<?php

namespace Modules\Authentication\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /**
     * Test user registration.
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john@example.com',
            'password' => 'SecretPass_2026!',
            'password_confirmation' => 'SecretPass_2026!',
            'pays' => 'France',
            'accept_terms' => true,
            'subscription_type' => 'mensuel',
            'role' => 'member',
        ]);

        if ($response->status() !== 201) {
            fwrite(STDERR, print_r($response->json(), true));
        }

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test user login.
     */
    public function test_user_can_login(): void
    {
        $user = User::create([
            'id' => Str::uuid()->toString(),
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane@example.com',
            'password' => Hash::make('SecretPass_2026!'),
            'email_verified_at' => now(),
            'statut_compte' => \Modules\Authentication\Enums\UserStatusEnum::ACTIF,
            'type_utilisateur' => \Modules\Authentication\Enums\UserRoleEnum::MEMBER,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'SecretPass_2026!',
        ]);

        if ($response->status() !== 200) {
            fwrite(STDERR, print_r($response->json(), true));
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                    'user',
                ],
            ]);
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::create([
            'id' => Str::uuid()->toString(),
            'nom' => 'Doe',
            'prenom' => 'Jack',
            'email' => 'jack@example.com',
            'password' => Hash::make('SecretPass_2026!'),
            'email_verified_at' => now(),
            'statut_compte' => \Modules\Authentication\Enums\UserStatusEnum::ACTIF,
            'type_utilisateur' => \Modules\Authentication\Enums\UserRoleEnum::MEMBER,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $this->assertCount(0, $user->fresh()->tokens);
    }

    /**
     * Test get current user.
     */
    public function test_user_can_get_me(): void
    {
        $user = User::create([
            'id' => Str::uuid()->toString(),
            'nom' => 'Doe',
            'prenom' => 'Jill',
            'email' => 'jill@example.com',
            'password' => Hash::make('SecretPass_2026!'),
            'email_verified_at' => now(),
            'statut_compte' => \Modules\Authentication\Enums\UserStatusEnum::ACTIF,
            'type_utilisateur' => \Modules\Authentication\Enums\UserRoleEnum::MEMBER,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => 'jill@example.com',
            ]);
    }

    /**
     * Test token refresh.
     */
    public function test_user_can_refresh_token(): void
    {
        $user = User::create([
            'id' => Str::uuid()->toString(),
            'nom' => 'Doe',
            'prenom' => 'Jim',
            'email' => 'jim@example.com',
            'password' => Hash::make('SecretPass_2026!'),
            'email_verified_at' => now(),
            'statut_compte' => \Modules\Authentication\Enums\UserStatusEnum::ACTIF,
            'type_utilisateur' => \Modules\Authentication\Enums\UserRoleEnum::MEMBER,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * Test forgot password.
     */
    public function test_user_can_request_password_reset(): void
    {
        $user = User::create([
            'id' => Str::uuid()->toString(),
            'nom' => 'Doe',
            'prenom' => 'JackReset',
            'email' => 'jackreset@example.com',
            'password' => Hash::make('SecretPass_2026!'),
            'statut_compte' => \Modules\Authentication\Enums\UserStatusEnum::ACTIF,
            'type_utilisateur' => \Modules\Authentication\Enums\UserRoleEnum::MEMBER,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'jackreset@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('password_resets', [
            'email' => 'jackreset@example.com',
        ]);
    }

    /**
     * Test 2FA enabling.
     */
    /* Commenting out 2FA test as dependencies cannot be installed in this environment due to GitHub rate limits
    public function test_user_can_enable_2fa(): void
    {
    }
    */
}

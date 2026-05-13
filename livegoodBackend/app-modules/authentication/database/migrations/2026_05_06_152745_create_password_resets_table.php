<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crée la table password_resets pour gérer les tokens de réinitialisation
     * Compatible avec le modèle PasswordReset du module Authentication
     */
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            // Identifiant UUID
            $table->uuid('id')->primary();
            
            // Email de l'utilisateur
            $table->string('email');
            
            // Token haché
            $table->string('token');
            
            // Dates avec expiration
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Index pour recherche rapide
            $table->index('email');
            $table->index(['email', 'token']);
        });

        // Table optionnelle pour logger les demandes de réinitialisation
        Schema::create('password_reset_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('requested_at');
            $table->timestamps();

            $table->index('email');
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_logs');
        Schema::dropIfExists('password_resets');
    }
};
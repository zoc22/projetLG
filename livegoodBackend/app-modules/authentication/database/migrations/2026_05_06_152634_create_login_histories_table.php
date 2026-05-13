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
     * Crée la table login_histories pour suivre toutes les connexions
     * Compatible avec le modèle LoginHistory du module Authentication
     */
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            // Identifiant UUID
            $table->uuid('id')->primary();
            
            // Référence à l'utilisateur
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Informations de connexion
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            
            // Statut de la tentative
            $table->boolean('login_successful')->default(true);
            $table->string('failure_reason')->nullable();
            
            // Identifiant de session (optionnel)
            $table->string('session_id')->nullable();
            
            // Timestamps
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index('user_id');
            $table->index('login_at');
            $table->index('ip_address');
            $table->index('login_successful');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
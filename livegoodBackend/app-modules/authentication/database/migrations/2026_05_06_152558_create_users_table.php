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
     * Crée la table users avec la structure complète pour LiveGood
     * Compatible avec le modèle User du module Authentication
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Identifiant UUID (clé primaire)
            $table->uuid('id')->primary();
            
            // Informations personnelles
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email')->unique();
            $table->string('password');
            
            // Localisation
            $table->string('pays', 100)->nullable();
            $table->string('devise', 3)->default('USD');
            
            // Statut et rôle
            $table->enum('statut_compte', [
                'actif', 'inactif', 'suspendu', 'bloque',
                'en_attente_verification', 'en_attente_approbation'
            ])->default('en_attente_verification');
            
            $table->enum('type_utilisateur', [
                'super_admin', 'admin', 'support', 'affiliate', 'member'
            ])->default('member');
            
            // Timestamps et dates
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('date_inscription')->useCurrent();
            $table->string('ip_inscription')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            
            // Avatar et réseaux sociaux
            $table->string('avatar')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->string('facebook_id')->nullable()->unique();
            $table->string('github_id')->nullable()->unique();
            
            // 2FA (Two Factor Authentication)
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Sécurité
            $table->integer('login_attempts')->default(0);
            $table->string('remember_token', 100)->nullable();
            
            // Soft delete et timestamps
            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les requêtes
            $table->index('email');
            $table->index('statut_compte');
            $table->index('type_utilisateur');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
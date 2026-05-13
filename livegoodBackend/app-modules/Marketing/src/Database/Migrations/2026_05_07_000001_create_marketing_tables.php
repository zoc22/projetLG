<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour les sites d'affiliation et les leads (pré-inscriptions).
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Sites personnalisés pour les affiliés
        Schema::create('affiliate_sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index(); // L'affilié propriétaire
            $table->string('type'); // SiteTypeEnum
            $table->string('slug')->unique(); // ex: livegood.com/zoccheri
            $table->string('custom_domain')->nullable()->unique();
            
            // Compteurs dénormalisés pour optimisation perfs (read-heavy)
            $table->integer('visits_count')->default(0);
            $table->integer('leads_count')->default(0);
            $table->integer('sales_count')->default(0);
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Leads (Pré-inscriptions)
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            
            $table->uuid('affiliate_id')->index(); // Qui l'a parrainé via son site
            $table->uuid('site_id')->nullable();    // Quel site spécifique a capturé le lead
            
            $table->timestamp('preenrolled_at');    // Date de pré-inscription
            $table->timestamp('cutoff_at')->nullable(); // Date limite (ex: Jeudi minuit)
            
            $table->string('status')->default('active'); // LeadStatusEnum
            $table->uuid('converted_user_id')->nullable(); // ID de l'utilisateur créé après paiement
            
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('affiliate_sites')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('affiliate_sites');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour le tracking des visites individuelles.
 * Design hautement optimisé pour l'insertion rapide (logs).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // L'affilié propriétaire du site qui a reçu la visite
            $table->uuid('affiliate_id')->index();

            // Type de site visité (Modules\Payment\Enums\TypeSite)
            $table->string('site_type'); 

            // Métadonnées techniques
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('source')->default('direct'); // VisitSourceEnum

            // Localisation géographique approximative
            $table->string('country_code', 2)->nullable()->index();
            
            $table->timestamps();

            // On ne met pas de foreign key cascade ici pour éviter de ralentir les deletes massifs d'users
            // sauf si c'est strictement requis par le business.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};

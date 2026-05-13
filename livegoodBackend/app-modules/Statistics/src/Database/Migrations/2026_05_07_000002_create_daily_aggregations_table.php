<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour le stockage des données agrégées.
 * Permet d'afficher des graphiques rapides sans scanner des millions de lignes de logs.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_aggregations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliate_id')->index();
            
            $table->date('reference_date')->index();
            
            // Stockage compact des compteurs de la journée
            $table->integer('visits_count')->default(0);
            $table->integer('preinscriptions_count')->default(0);
            $table->integer('conversions_count')->default(0);
            $table->decimal('revenue_amount', 15, 2)->default(0.00);

            $table->unique(['affiliate_id', 'reference_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_aggregations');
    }
};

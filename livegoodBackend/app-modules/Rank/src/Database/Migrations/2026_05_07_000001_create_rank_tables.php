<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Historique des passages de rangs
        Schema::create('user_rank_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('old_rank')->nullable();
            $table->string('new_rank');
            $table->json('metadata')->nullable(); // Détails sur la raison du passage
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Table pour mettre en cache les compteurs de rang (pour optimisation perfs)
        // Normalement ce serait dans GenealogyNode, mais ici on isole les compteurs
        Schema::create('rank_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->integer('direct_active_count')->default(0);
            $table->integer('total_team_active_count')->default(0);
            // Stockage JSON des rangs par branche (leg_id => best_rank_in_leg)
            $table->json('legs_data')->nullable(); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rank_counters');
        Schema::dropIfExists('user_rank_histories');
    }
};

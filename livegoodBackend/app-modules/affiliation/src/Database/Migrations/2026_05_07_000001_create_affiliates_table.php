<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour étendre ou gérer les données spécifiques aux affiliés.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Table centrale pour les données métier d'affiliation (liée à Users)
        Schema::create('affiliates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->index();
            
            $table->string('username_canonical')->unique(); // Pseudo utilisé pour les liens
            $table->string('referral_code')->unique();
            
            $table->string('status')->default('pending'); // AffiliationStatusEnum
            $table->string('rank')->default('unranked');   // RankEnum
            
            // Stats financières dénormalisées
            $table->decimal('total_commissions', 15, 2)->default(0.00);
            $table->decimal('pending_balance', 15, 2)->default(0.00);
            
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Historique des changements de rang
        Schema::create('rank_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliate_id')->index();
            $table->string('old_rank');
            $table->string('new_rank');
            $table->json('reason_metadata')->nullable();
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rank_histories');
        Schema::dropIfExists('affiliates');
    }
};

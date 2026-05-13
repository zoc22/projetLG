<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matching_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('commission_id')->unique();
            $table->uuid('source_affiliate_id'); // L'affilié dont on match les gains
            $table->decimal('source_matrix_amount', 12, 2);
            $table->decimal('match_percentage', 5, 2);
            $table->timestamps();

            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matching_bonuses');
    }
};

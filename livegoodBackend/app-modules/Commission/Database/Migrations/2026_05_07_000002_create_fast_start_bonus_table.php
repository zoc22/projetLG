<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fast_start_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('commission_id')->unique();
            $table->uuid('downline_id'); // Le nouveau membre qui s'est inscrit
            $table->integer('level'); // Niveau de profondeur
            $table->decimal('percentage', 5, 2);
            $table->timestamps();

            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fast_start_bonuses');
    }
};

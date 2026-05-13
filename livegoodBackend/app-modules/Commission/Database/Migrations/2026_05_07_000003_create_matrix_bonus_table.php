<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matrix_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('commission_id')->unique();
            $table->integer('active_members_count');
            $table->timestamps();

            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matrix_bonuses');
    }
};

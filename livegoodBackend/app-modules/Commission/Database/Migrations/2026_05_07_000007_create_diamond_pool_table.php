<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diamond_pools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('commission_id')->unique();
            $table->decimal('total_company_revenue', 18, 2);
            $table->integer('eligible_diamonds_count');
            $table->timestamps();

            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diamond_pools');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('influencer_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('commission_id')->unique();
            $table->decimal('monthly_sales_volume', 15, 2);
            $table->decimal('extra_percentage', 5, 2);
            $table->timestamps();

            $table->foreign('commission_id')->references('id')->on('commissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('influencer_bonuses');
    }
};

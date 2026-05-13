<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // Modules\Commission\Enums\CommissionStatusEnum
            $table->string('type'); // Modules\Commission\Enums\CommissionTypeEnum
            $table->string('source')->nullable(); // ID de la commande ou de l'inscription source
            $table->string('period_string')->index(); // ex: "2024-W01" ou "2024-M01"
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};

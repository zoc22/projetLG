<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour la gestion des abonnements (Le moteur du revenu résiduel).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            
            $table->string('type'); // monthly, annual
            $table->string('status')->default('active'); // active, expired, canceled
            
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamp('next_billing_at')->nullable();
            
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

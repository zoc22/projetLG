<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour la gestion des paiements et transactions.
 * Design hautement optimisé avec indexation par utilisateur et par statut.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Méthodes de paiement enregistrées par les membres
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('type'); // PaymentMethodEnum
            $table->json('details'); // Stockage sécurisé des identifiants (IBAN masqué, wallet addr, etc)
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Transactions financières (Paiement commissions ou achat abonnement)
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('method'); // PaymentMethodEnum
            $table->string('status')->default('pending'); // PaymentStatusEnum
            $table->string('reference')->unique()->nullable(); // ID transaction gateway
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Demandes de retrait (Payouts demandés par les affiliés)
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('payment_method_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('fees', 10, 2)->default(0.00);
            $table->decimal('net_amount', 15, 2);
            $table->string('status')->default('requested'); // WithdrawalStatusEnum
            $table->text('rejection_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour le système de support et ticketing.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Tickets (Sujet principal)
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index(); // L'utilisateur qui demande de l'aide
            
            $table->string('subject');
            $table->string('status')->default('open'); // TicketStatusEnum
            $table->string('priority')->default('medium'); // low, medium, high
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Messages (Discussion au sein du ticket)
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id')->index();
            $table->uuid('user_id')->index(); // Auteur du message (User ou Admin)
            
            $table->text('content');
            $table->json('attachments')->nullable();
            
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
    }
};

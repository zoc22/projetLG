<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table Genealogy Nodes (Unilevel Tree)
        Schema::create('genealogy_nodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->index();
            $table->uuid('sponsor_id')->nullable()->index();
            $table->string('path', 2048)->index(); // Chemin matérialisé pour perfs
            $table->integer('depth')->default(0);
            $table->string('rank')->default('UNRANKED');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Table Positions (Binary Matrix Tree)
        Schema::create('positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->unique()->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->uuid('placer_id')->index();
            $table->integer('level')->index();
            $table->integer('position_index');
            $table->enum('side', ['left', 'right'])->nullable();
            $table->string('matrix_path', 1024)->index();
            $table->timestamps();
        });

        // Self-referencing FK must be added after the table is created in PostgreSQL
        Schema::table('positions', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('positions')->onDelete('set null');
        });

        // 3. Table Matrices (Stats & Bonus)
        Schema::create('matrices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_id')->index();
            $table->integer('max_depth')->default(15);
            $table->integer('total_members')->default(0);
            $table->decimal('monthly_bonus', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('owner_id')->references('user_id')->on('genealogy_nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrices');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('genealogy_nodes');
    }
};

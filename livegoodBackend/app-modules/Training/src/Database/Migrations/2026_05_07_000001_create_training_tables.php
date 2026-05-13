<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour les formations, webinaires et formateurs.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Formateurs (Experts système)
        Schema::create('trainers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('role'); // ex: Top Earner, CEO, Product Expert
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });

        // 2. Formations (Modules vidéo/textuels)
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('trainer_id')->index();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('level'); // TrainingLevelEnum
            
            $table->string('video_url')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_certifying')->default(false);
            
            $table->json('prerequisites')->nullable(); // Liste de slugs ou IDs
            
            $table->timestamps();
            $table->foreign('trainer_id')->references('id')->on('trainers');
        });

        // 3. Suivi des participants (Enrollments)
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('training_id')->index();
            
            $table->integer('progress_percentage')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->string('certificate_path')->nullable();
            
            $table->timestamps();
            
            $table->unique(['user_id', 'training_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
        });

        // 4. Webinaires (Sessions en direct ou Replays)
        Schema::create('webinars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('trainer_id')->index();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at');
            $table->string('language', 5)->default('en');
            
            $table->string('zoom_link')->nullable();
            $table->string('replay_url')->nullable();
            
            $table->timestamps();
            $table->foreign('trainer_id')->references('id')->on('trainers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinars');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('trainers');
    }
};

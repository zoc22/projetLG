<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour les liens d'affiliation générés.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliate_id')->index();
            
            $table->string('name'); // Nom du lien (ex: Retail Store, Landing Page)
            $table->string('slug')->unique(); // Slug final utilisé dans l'URL
            $table->string('target_type'); // corporate, retail, capture
            
            $table->integer('clicks_count')->default(0);
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};

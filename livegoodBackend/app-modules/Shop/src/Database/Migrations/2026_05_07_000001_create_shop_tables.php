<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour les produits, commandes et panier.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Produits (Bien-être LiveGood)
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            
            // Prix différenciés (Philosophie LiveGood: prix coûtant pour membres)
            $table->decimal('public_price', 10, 2);
            $table->decimal('member_price', 10, 2);
            
            $table->string('category')->index();
            $table->json('ingredients')->nullable();
            $table->integer('stock')->default(0);
            $table->string('image_url')->nullable();
            
            $table->timestamps();
        });

        // 2. Commandes
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index(); // Acheteur
            $table->uuid('affiliate_id')->nullable()->index(); // Affilié qui a fait la vente
            
            $table->timestamp('ordered_at');
            $table->string('status')->default('pending'); // Enums\OrderStatusEnum
            
            $table->decimal('total_amount', 12, 2);
            $table->string('shipping_method')->nullable();
            $table->text('shipping_address')->nullable();
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Lignes de commande
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->index();
            $table->uuid('product_id')->index();
            
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};

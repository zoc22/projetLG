<?php

use Modules\Shop\Models\Product;

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $products = [
        [
            'name' => 'Bio-Active Complete Multi-Vitamin',
            'description' => 'For Men with Immune Support',
            'public_price' => 17.95,
            'member_price' => 9.95,
            'stock' => 1000,
            'category' => 'Vitamines'
        ],
        [
            'name' => 'Complete Plant-Based Protein',
            'description' => 'Vanilla Flavor',
            'public_price' => 34.00,
            'member_price' => 22.00,
            'stock' => 500,
            'category' => 'Nutrition'
        ],
        [
            'name' => 'Organic Super Reds',
            'description' => 'Cardiovascular Powerhouse',
            'public_price' => 29.95,
            'member_price' => 18.00,
            'stock' => 750,
            'category' => 'Bien-être'
        ]
    ];

    foreach ($products as $p) {
        Product::updateOrCreate(['name' => $p['name']], $p);
    }

    echo "Produits créés avec succès.\n";

} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

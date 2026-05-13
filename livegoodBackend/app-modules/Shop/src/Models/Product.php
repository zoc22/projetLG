<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Modèle pour un produit physique (Compléments alimentaires, etc.).
 */
class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'public_price', 'member_price', 
        'category', 'ingredients', 'stock', 'image_url'
    ];

    protected $casts = [
        'ingredients'  => 'array',
        'public_price' => 'decimal:2',
        'member_price' => 'decimal:2'
    ];

    /**
     * Calcule la marge brute sur laquelle est payée la commission affilié (50% de la diff).
     */
    public function getPriceDifference(): float
    {
        return (float) ($this->public_price - $this->member_price);
    }

    /**
     * Vérifie la disponibilité du stock.
     */
    public function isAvailable(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Calcule la commission théorique pour l'affilié par unité vendue au public.
     * LiveGood règle: 50% de la différence entre prix public et membre.
     */
    public function calculateAffiliateCommission(): float
    {
        return $this->getPriceDifference() * 0.5;
    }
}

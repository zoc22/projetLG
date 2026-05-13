<?php

namespace Modules\Shop\Services;

use Modules\Shop\Models\Product;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Service de gestion du panier d'achat.
 * Utilise le Cache pour la persistence temporaire (optimisation I/O).
 */
class CartService
{
    /**
     * Ajoute un article au panier en cache.
     */
    public function addToCart(string $userId, string $productId, int $quantity): void
    {
        $cart = $this->getCart($userId);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'id'       => $productId,
                'name'     => $product->name,
                'price'    => $product->member_price, // Par défaut prix membre si connecté
                'quantity' => $quantity
            ];
        }

        Cache::put("cart_{$userId}", $cart, now()->addDays(7));
    }

    /**
     * Récupère le panier.
     */
    public function getCart(string $userId): array
    {
        return Cache::get("cart_{$userId}", []);
    }

    /**
     * Calcule le total du panier.
     */
    public function calculateTotal(string $userId): float
    {
        $cart = $this->getCart($userId);
        return array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0.0);
    }

    /**
     * Valide le panier et crée une commande réelle en base.
     */
    public function checkout(string $userId, array $shippingData): Order
    {
        return DB::transaction(function() use ($userId, $shippingData) {
            $cart = $this->getCart($userId);
            if (empty($cart)) throw new \Exception("Le panier est vide.");

            $order = Order::create([
                'user_id'          => $userId,
                'ordered_at'       => now(),
                'status'           => 'pending',
                'total_amount'     => $this->calculateTotal($userId),
                'shipping_method'  => $shippingData['method'] ?? 'standard',
                'shipping_address' => $shippingData['address'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price']
                ]);
                
                // Décrémenter le stock
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            // Vider le panier
            Cache::forget("cart_{$userId}");

            return $order;
        });
    }
}

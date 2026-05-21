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
        $user = \Modules\Authentication\Models\User::find($userId);
        
        // Déterminer le prix (membre ou public)
        $product = Product::findOrFail($productId);
        $isMember = $user && $user->subscription && $user->subscription->status === 'active';
        $price = $isMember ? $product->member_price : $product->public_price;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['price'] = $price; // Update price in case status changed
        } else {
            $cart[$productId] = [
                'id'       => $productId,
                'name'     => $product->name,
                'price'    => $price,
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

            $user = \Modules\Authentication\Models\User::find($userId);
            $isMember = $user && $user->subscription && $user->subscription->status === 'active';

            $order = Order::create([
                'user_id'          => $userId,
                'ordered_at'       => now(),
                'status'           => 'completed', // On simule le paiement réussi pour les tests
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
                
                // Déclencher le bonus de vente au détail si non-membre
                if (!$isMember) {
                    $this->triggerRetailBonus($userId, $item['id'], $item['quantity']);
                }

                // Décrémenter le stock
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            // Vider le panier
            Cache::forget("cart_{$userId}");

            return $order;
        });
    }

    /**
     * Déclenche le calcul du bonus de vente au détail.
     */
    private function triggerRetailBonus(string $userId, string $productId, int $quantity): void
    {
        try {
            // Récupérer le sponsor via la généalogie
            $node = \Modules\Genealogy\Models\GenealogyNode::where('user_id', $userId)->first();
            if ($node && $node->sponsor_id) {
                $product = Product::find($productId);
                $retailBonusAction = app(\Modules\Commission\Actions\CalculateRetailBonus::class);
                
                // On passe les détails pour le calcul
                $retailBonusAction->execute(
                    $node->sponsor_id, 
                    $userId, 
                    (float)($product->public_price - $product->member_price) * $quantity
                );
            }
        } catch (\Exception $e) {
            \Log::error("Erreur déclenchement Retail Bonus: " . $e->getMessage());
        }
    }
}

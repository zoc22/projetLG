<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shop\Services\CartService;
use Modules\Shop\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion du catalogue et du processus d'achat.
 */
class ShopController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Voir le catalogue produits.
     */
    public function index(): JsonResponse
    {
        $products = Product::where('stock', '>', 0)->get();
        return response()->json($products);
    }

    /**
     * Ajouter un produit au panier.
     */
    public function addToCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $this->cartService->addToCart(Auth::id(), $validated['product_id'], $validated['quantity']);

        return response()->json(['message' => 'Produit ajouté au panier.']);
    }

    /**
     * Finaliser l'achat.
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'shipping_method'  => 'required|string'
        ]);

        $order = $this->cartService->checkout(Auth::id(), [
            'address' => $validated['shipping_address'],
            'method'  => $validated['shipping_method']
        ]);

        return response()->json([
            'message'  => 'Commande validée avec succès.',
            'order_id' => $order->id
        ], 201);
    }
}

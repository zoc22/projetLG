<?php

namespace Modules\Affiliation\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Affiliation\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Affiliation\Enums\SubscriptionTypeEnum;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion des abonnements de l'affilié.
 */
class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $service) {}

    /**
     * Voir l'état de mon abonnement.
     */
    public function status(): JsonResponse
    {
        $isActive = $this->service->checkStatus(Auth::id());
        return response()->json(['is_active' => $isActive]);
    }

    /**
     * Prolonger ou changer d'abonnement.
     */
    public function upgrade(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan' => 'required|string|in:monthly,annual'
        ]);

        $plan = SubscriptionTypeEnum::from($validated['plan']);
        $sub = $this->service->subscribe(Auth::id(), $plan);

        return response()->json([
            'message' => 'Subscription upgraded.',
            'ends_at' => $sub->ends_at->toDateString()
        ]);
    }
}

<?php

namespace Modules\Marketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Marketing\Services\MarketingService;
use Modules\Marketing\Models\AffiliateSite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion du marketing et des leads pour l'affilié.
 */
class MarketingController extends Controller
{
    public function __construct(protected MarketingService $marketingService) {}

    /**
     * Liste mes sites d'affiliation.
     */
    public function mySites(): JsonResponse
    {
        $sites = AffiliateSite::where('user_id', Auth::id())->get();
        return response()->json($sites);
    }

    /**
     * Liste mes leads (pré-inscrits).
     */
    public function myLeads(): JsonResponse
    {
        $leads = $this->marketingService->getActiveLeadsForAffiliate(Auth::id());
        return response()->json($leads);
    }

    /**
     * Simulation de capture (silencieuse ou via formulaire public).
     */
    public function publicCapture(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|unique:leads,email',
            'affiliate_id' => 'required|uuid',
            'site_id'      => 'nullable|uuid'
        ]);

        $lead = $this->marketingService->captureLead($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pré-inscription réussie.',
            'lead_id' => $lead->id
        ], 201);
    }
}

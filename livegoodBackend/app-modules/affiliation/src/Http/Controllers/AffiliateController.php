<?php

namespace Modules\Affiliation\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Affiliation\Services\AffiliateService;
use Modules\Affiliation\Http\Resources\AffiliateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur gérant les données de l'affilié.
 */
class AffiliateController extends Controller
{
    public function __construct(protected AffiliateService $service) {}

    /**
     * Dashboard Affilié : Retourne les stats business.
     */
    public function dashboard(): JsonResponse
    {
        $data = $this->service->getBusinessSummary(Auth::id());
        return response()->json($data);
    }

    /**
     * Profil complet de l'affilié.
     */
    public function me(): AffiliateResource
    {
        $affiliate = \Modules\Affiliation\Models\Affiliate::where('user_id', Auth::id())->firstOrFail();
        return new AffiliateResource($affiliate);
    }
}

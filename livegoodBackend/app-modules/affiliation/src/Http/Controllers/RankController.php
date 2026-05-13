<?php

namespace Modules\Affiliation\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Affiliation\Services\RankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Affiliation\Models\Affiliate;

/**
 * Gestion des rangs et qualifications.
 */
class RankController extends Controller
{
    public function __construct(protected RankService $service) {}

    /**
     * Voir mon historique de qualification.
     */
    public function history(): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::id())->firstOrFail();
        return response()->json($affiliate->rankHistory);
    }

    /**
     * Forcer une vérification de qualification (Trigger manuel).
     */
    public function check(): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::id())->firstOrFail();
        $this->service->evaluateQualification($affiliate->id);
        
        return response()->json(['message' => 'Qualification check completed.']);
    }
}

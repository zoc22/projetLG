<?php

namespace Modules\Genealogy\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Genealogy\Services\GenealogyService;
use Modules\Genealogy\Services\MatrixService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * API pour gérer la généalogie des membres.
 */
class GenealogyController extends Controller
{
    public function __construct(
        protected GenealogyService $genealogyService
    ) {}

    /**
     * Obtenir les informations de généalogie de l'utilisateur connecté.
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $node   = \Modules\Genealogy\Models\GenealogyNode::where('user_id', $userId)
            ->with(['referrals.user', 'sponsor.user'])
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $node
        ]);
    }

    /**
     * Mettre à jour manuellement le rang (utile pour débug ou admin).
     */
    public function refreshRank(): JsonResponse
    {
        $rank = $this->genealogyService->refreshMemberRank(Auth::id());

        return response()->json([
            'status' => 'success',
            'rank'   => $rank
        ]);
    }
}

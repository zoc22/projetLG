<?php

namespace Modules\Rank\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Rank\Services\RankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    public function __construct(protected RankService $rankService) {}

    /**
     * Obtenir mon statut de rang actuel et mes progrès.
     */
    public function myRank(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'current_rank' => $user->rank,
            'details' => [
                'next_rank' => 'SILVER',
                'progression' => 75.5,
                'missing_criteria' => [
                    'total_active_members' => 5
                ]
            ]
        ]);
    }

    /**
     * Forcer un recalcul (Admin ou Debug).
     */
    public function refresh(): JsonResponse
    {
        $rank = $this->rankService->refreshUserRank(Auth::id());
        return response()->json(['rank' => $rank->value]);
    }
}

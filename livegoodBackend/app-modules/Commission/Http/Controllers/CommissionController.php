<?php

namespace Modules\Commission\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Commission\Models\Commission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Voir l'historique de mes commissions.
     */
    public function index(): JsonResponse
    {
        $commissions = Commission::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($commissions);
    }

    /**
     * Résumé par période.
     */
    public function summaryPerPeriod(string $period): JsonResponse
    {
        $summary = Commission::where('user_id', Auth::id())
            ->where('period_string', $period)
            ->selectRaw('status, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        return response()->json($summary);
    }
}

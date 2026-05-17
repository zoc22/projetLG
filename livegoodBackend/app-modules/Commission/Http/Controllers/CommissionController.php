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
     * Résumé global pour la page "Mes Gains".
     */
    public function earnings(): JsonResponse
    {
        $userId = Auth::id();
        
        $totalPaid = Commission::where('user_id', $userId)
            ->where('status', 'VALIDATED')
            ->sum('amount');

        // Récupérer l'historique groupé par période
        $history = Commission::where('user_id', $userId)
            ->selectRaw('period_string as range, MAX(created_at) as date, 
                         SUM(CASE WHEN type = "WEEKLY" THEN amount ELSE 0 END) as fs,
                         SUM(CASE WHEN type = "MONTHLY" THEN amount ELSE 0 END) as matrix,
                         SUM(amount) as total')
            ->groupBy('period_string')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'range'  => $item->range,
                    'date'   => $item->date->format('d/m/Y'),
                    'fs'     => number_format($item->fs, 2, '.', ''),
                    'matrix' => number_format($item->matrix, 2, '.', ''),
                    'total'  => number_format($item->total, 2, '.', '')
                ];
            });

        return response()->json([
            'lifeTimeEarnings' => (float)$totalPaid,
            'history' => $history
        ]);
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

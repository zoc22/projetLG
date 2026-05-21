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

    /**
     * Historique des bonus filtré par type.
     */
    public function bonusHistory(): JsonResponse
    {
        $type = request('type');
        $query = Commission::where('user_id', Auth::id());

        if ($type) {
            $query->where('bonus_type', $type);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(20));
    }

    /**
     * Gains mensuels consolidés.
     */
    public function monthlyEarnings(): JsonResponse
    {
        $earnings = Commission::where('user_id', Auth::id())
            ->where('type', 'MONTHLY')
            ->selectRaw("SUBSTRING(period_string, 1, 7) as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json($earnings);
    }

    /**
     * Gains annuels consolidés.
     */
    public function yearlyEarnings(): JsonResponse
    {
        $earnings = Commission::where('user_id', Auth::id())
            ->selectRaw("SUBSTRING(period_string, 1, 4) as year, SUM(amount) as total")
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return response()->json($earnings);
    }

    /**
     * Rapport détaillé.
     */
    public function report(): JsonResponse
    {
        $startDate = request('start_date');
        $endDate = request('end_date');

        $query = Commission::where('user_id', Auth::id());

        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        return response()->json($query->get());
    }
}

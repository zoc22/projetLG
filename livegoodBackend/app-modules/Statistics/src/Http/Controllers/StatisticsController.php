<?php

namespace Modules\Statistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Statistics\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur principal pour les rapports statistiques.
 */
class StatisticsController extends Controller
{
    public function __construct(protected StatisticsService $statsService) {}

    /**
     * Retourne un résumé global des KPIs pour l'utilisateur connecté.
     */
    public function summary(): JsonResponse
    {
        $userId = Auth::id();

        return response()->json([
            'conversion_rate' => $this->statsService->getConversionRate($userId),
            'activation_rate' => $this->statsService->getActivationRate($userId),
            'weekly_growth'   => $this->statsService->getWeeklyGrowth($userId),
            'team_volume'     => $this->statsService->getTeamVolume($userId),
            'total_earnings'  => $this->statsService->getTotalEarnings($userId),
            'active_members'  => $this->statsService->getActiveMembersCount($userId),
            'last_30_days'    => $this->statsService->getTimeSeries($userId, 30)
        ]);
    }
}

<?php

namespace Modules\Statistics\Services;

use Modules\Statistics\Models\DailyAggregation;
use Illuminate\Support\Facades\DB;
use Modules\Statistics\Enums\MetricTypeEnum;

/**
 * Service de calcul des statistiques business complexes.
 * Implémente la logique décrite dans le diagramme de classe (tauxConversion, croissance, etc.).
 */
class StatisticsService
{
    /**
     * Calcule le taux de conversion global (Visites -> Inscriptions Payées) pour un utilisateur.
     * Relation : (conversions / visites) * 100
     */
    public function getConversionRate(string $userId, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = DailyAggregation::where('affiliate_id', $userId);

        if ($startDate) $query->where('reference_date', '>=', $startDate);
        if ($endDate)   $query->where('reference_date', '<=', $endDate);

        $totals = $query->selectRaw('SUM(visits_count) as total_visits, SUM(conversions_count) as total_conversions')
            ->first();

        if (!$totals || $totals->total_visits == 0) {
            return 0.0;
        }

        return round(($totals->total_conversions / $totals->total_visits) * 100, 2);
    }

    /**
     * Calcule le taux d'activation (Pre-inscrits -> Inscrits Payés).
     * Crucial pour mesurer l'efficacité de l'entonnoir LiveGood.
     */
    public function getActivationRate(string $userId): float
    {
        $totals = DailyAggregation::where('affiliate_id', $userId)
            ->selectRaw('SUM(preinscriptions_count) as leads, SUM(conversions_count) as sales')
            ->first();

        if (!$totals || $totals->leads == 0) return 0.0;

        return round(($totals->sales / $totals->leads) * 100, 2);
    }

    /**
     * Calcule la croissance hebdomadaire de l'équipe (en pourcentage).
     */
    public function getWeeklyGrowth(string $userId): float
    {
        // On récupère le volume de conversion de cette semaine vs semaine dernière
        $currentWeek = DailyAggregation::where('affiliate_id', $userId)
            ->whereBetween('reference_date', [now()->startOfWeek(), now()])
            ->sum('conversions_count');

        $lastWeek = DailyAggregation::where('affiliate_id', $userId)
            ->whereBetween('reference_date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('conversions_count');

        if ($lastWeek == 0) return $currentWeek > 0 ? 100.0 : 0.0;

        return round((($currentWeek - $lastWeek) / $lastWeek) * 100, 2);
    }

    /**
     * Calcule le volume total d'équipe (Chiffre d'affaires cumulé).
     */
    public function getTeamVolume(string $userId): float
    {
        return (float) DailyAggregation::where('affiliate_id', $userId)
            ->sum('revenue_amount');
    }

    /**
     * Calcule le total des gains (commissions) perçus.
     * Note: Dans cette implémentation, on agrège via les données journalières.
     */
    public function getTotalEarnings(string $userId): float
    {
        // On pourrait aussi requêter le module Commission, mais ici on suit le modèle Stats.
        return (float) DailyAggregation::where('affiliate_id', $userId)
            ->sum('revenue_amount') * 0.5; // Exemple: 50% de commission moyenne
    }

    /**
     * Compte les membres actifs dans l'équipe.
     */
    public function getActiveMembersCount(string $userId): int
    {
        // On utilise la valeur la plus récente de l'agrégation ou une requête sur Users
        return (int) DailyAggregation::where('affiliate_id', $userId)
            ->orderBy('reference_date', 'desc')
            ->value('conversions_count') ?? 0;
    }

    /**
     * Récupère les données temporelles pour un graphique.
     */
    public function getTimeSeries(string $userId, int $days = 30): array
    {
        return DailyAggregation::where('affiliate_id', $userId)
            ->where('reference_date', '>=', now()->subDays($days))
            ->orderBy('reference_date', 'asc')
            ->get()
            ->toArray();
    }
}

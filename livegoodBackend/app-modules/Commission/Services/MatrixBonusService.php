<?php

namespace Modules\Commission\Services;

/**
 * Gère le bonus matriciel mensuel ($0.25 par membre actif).
 */
class MatrixBonusService
{
    public function __construct(protected CommissionService $commissionService) {}

    /**
     * Calcul global pour une période donnée.
     */
    public function runMonthlyCalculations(string $periodString): void
    {
        // On bouclerait sur tous les membres actifs ici.
    }
}

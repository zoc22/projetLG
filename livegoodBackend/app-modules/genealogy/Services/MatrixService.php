<?php

namespace Modules\Genealogy\Services;

use Modules\Genealogy\Models\Position;
use Modules\Genealogy\Models\GenealogyNode;
use Modules\Genealogy\Enums\MatrixLevelEnum;

/**
 * Service pour la gestion des données de la matrice et des gains.
 */
class MatrixService
{
    /**
     * Calcule le bonus matriciel ($0.25 par membre sous soi dans la limite du rang).
     */
    public function calculateUserBonus(string $userId): float
    {
        $node = GenealogyNode::where('user_id', $userId)->first();
        $pos = Position::where('user_id', $userId)->first();

        if (!$pos || !$node) return 0.0;

        // Limite de profondeur autorisée par le rang actuel (selon le PDF)
        $maxRelativeDepth = MatrixLevelEnum::getMaxDepthForRank($node->rank);
        $maxAbsoluteLevel = $pos->level + $maxRelativeDepth;

        // Requête optimisée : Compter les membres actifs dans la sous-matrice
        $count = Position::query()
            ->join('genealogy_nodes', 'positions.user_id', '=', 'genealogy_nodes.user_id')
            ->where('positions.matrix_path', 'like', $pos->matrix_path . '.%')
            ->where('positions.level', '<=', $maxAbsoluteLevel)
            ->where('genealogy_nodes.is_active', true)
            ->count();

        return $count * config('genealogy.matrix.commission_per_member', 0.25);
    }
}

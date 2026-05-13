<?php

namespace Modules\Genealogy\Services;

use Modules\Genealogy\Models\Position;

/**
 * Service pour transformer la structure à plat en structure hiérarchique (JSON).
 */
class TreeBuilderService
{
    /**
     * Construit l'arbre récursivement à partir d'un utilisateur racine.
     */
    public function buildMatrixTree(string $rootUserId, int $maxDepth = 3): array
    {
        $rootPos = Position::where('user_id', $rootUserId)->first();

        if (!$rootPos) return [];

        return $this->formatNode($rootPos, 0, $maxDepth);
    }

    private function formatNode(Position $pos, int $currentLevel, int $limit): array
    {
        $node = [
            'id'       => $pos->id,
            'side'     => $pos->side,
            'userName' => $pos->occupant?->user?->name ?? 'Emplacement Vide',
            'rank'     => $pos->occupant?->rank ?? null
        ];

        if ($currentLevel < $limit) {
            $node['children'] = $pos->children()
                ->orderBy('side', 'asc')
                ->get()
                ->map(fn($child) => $this->formatNode($child, $currentLevel + 1, $limit))
                ->toArray();
        }

        return $node;
    }
}

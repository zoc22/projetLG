<?php

namespace Modules\Genealogy\Services;

use Modules\Genealogy\Models\Position;
use Illuminate\Support\Facades\DB;

/**
 * Gère le débordement (Spillover) dans la matrice forcée 2x15.
 */
class SpilloverService
{
    /**
     * Trouve le meilleur emplacement disponible sous un parrain (Sponsor).
     * Méthode : Top-to-Bottom, Left-to-Right.
     * Optimize : Utilise des comptages par niveau relatifs pour sauter les niveaux pleins.
     */
    public function findBestPosition(string $sponsorUserId): array
    {
        // 1. Récupérer la position du parrain
        $sponsorPos = Position::where('user_id', $sponsorUserId)->first();

        if (!$sponsorPos) {
            throw new \Exception("Le parrain n'est pas positionné dans la matrice.");
        }

        // 2. Parcourir les niveaux sous le parrain (limité à 15 niveaux max)
        for ($depth = 1; $depth <= 15; $depth++) {
            $targetLevel = $sponsorPos->level + $depth;
            $maxNodesAtLevel = pow(2, $depth);

            // Vérifier si le niveau est plein
            $count = Position::where('matrix_path', 'like', $sponsorPos->matrix_path . '.%')
                ->where('level', $targetLevel)
                ->count();

            if ($count < $maxNodesAtLevel) {
                // Ce niveau a au moins une place. On cherche la première de gauche à droite.
                return $this->searchFirstEmptySpot($sponsorPos, $targetLevel);
            }
        }

        throw new \Exception("Matrice saturée sous ce parrain pour les 15 prochains niveaux.");
    }

    /**
     * Recherche récursive ou par itération sur les parents du niveau N-1 
     * pour trouver un enfant manquant au niveau N.
     */
    private function searchFirstEmptySpot(Position $root, int $targetLevel): array
    {
        // Récupérer tous les parents potentiels au niveau juste au-dessus
        $parents = Position::where('matrix_path', 'like', $root->matrix_path . '.%')
            ->orWhere('id', $root->id)
            ->where('level', $targetLevel - 1)
            ->orderBy('level', 'asc')
            ->orderBy('position_index', 'asc')
            ->get();

        foreach ($parents as $parent) {
            $occupiedSides = $parent->children()->pluck('side')->toArray();
            
            // Priorité : Gauche puis Droite
            if (!in_array('left', $occupiedSides)) {
                return ['parent_id' => $parent->id, 'side' => 'left', 'index' => 0];
            }
            if (!in_array('right', $occupiedSides)) {
                return ['parent_id' => $parent->id, 'side' => 'right', 'index' => 1];
            }
        }

        throw new \Exception("Anomalie : Aucun slot libre trouvé dans un niveau pourtant incomplet.");
    }
}

<?php

namespace Modules\Genealogy\Actions;

use Modules\Genealogy\Models\GenealogyNode;
use Modules\Genealogy\Models\Position;
use Modules\Genealogy\Services\SpilloverService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Action atomique pour ajouter un membre à la structure.
 */
class AddToGenealogy
{
    public function __construct(protected SpilloverService $spilloverService) {}

    public function execute(string $userId, ?string $sponsorId): GenealogyNode
    {
        return DB::transaction(function () use ($userId, $sponsorId) {
            // 1. Créer le nœud dans l'arbre unilateral (Enrolment Tree)
            $sponsorNode = $sponsorId ? GenealogyNode::where('user_id', $sponsorId)->first() : null;
            
            $node = GenealogyNode::create([
                'user_id'    => $userId,
                'sponsor_id' => $sponsorId,
                'depth'      => $sponsorNode ? $sponsorNode->depth + 1 : 0,
                'path'       => $sponsorNode ? $sponsorNode->path . '.' . $userId : $userId,
                'rank'       => 'UNRANKED',
                'is_active'  => true
            ]);

            // 2. Trouver la place dans la matrice binaire (Forced Matrix)
            $this->placeInMatrix($userId, $sponsorId);

            return $node;
        });
    }

    private function placeInMatrix(string $userId, ?string $sponsorId): void
    {
        if ($sponsorId) {
            // On cherche le premier slot vide sous le parrain
            $bestSpot = $this->spilloverService->findBestPosition($sponsorId);
            $parentPos = Position::findOrFail($bestSpot['parent_id']);

            Position::create([
                'user_id'        => $userId,
                'parent_id'      => $parentPos->id,
                'placer_id'      => $sponsorId,
                'level'          => $parentPos->level + 1,
                'position_index' => $bestSpot['index'],
                'side'           => $bestSpot['side'],
                'matrix_path'    => $parentPos->matrix_path . '.' . substr($userId, 0, 8)
            ]);
        } else {
            // Racine du système
            Position::create([
                'user_id'        => $userId,
                'level'          => 0,
                'position_index' => 0,
                'matrix_path'    => substr($userId, 0, 8)
            ]);
        }
    }
}

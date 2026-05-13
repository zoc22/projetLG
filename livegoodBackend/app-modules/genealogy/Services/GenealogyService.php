<?php

namespace Modules\Genealogy\Services;

use Modules\Genealogy\Models\GenealogyNode;
use Modules\Genealogy\Actions\AddToGenealogy;

/**
 * Service de haut niveau pour les opérations de généalogie.
 */
class GenealogyService
{
    public function __construct(
        protected AddToGenealogy $addToGenealogyAction
    ) {}

    /**
     * Enregistrer un nouveau membre dans l'arbre unilevel et la matrice forcée.
     */
    public function addNewMember(string $userId, ?string $sponsorId): GenealogyNode
    {
        return $this->addToGenealogyAction->execute($userId, $sponsorId);
    }

    /**
     * Met à jour le rang d'un membre selon les critères (simplifié ici).
     */
    public function refreshMemberRank(string $userId): string
    {
        $node = GenealogyNode::where('user_id', $userId)->first();
        if (!$node) return 'UNRANKED';

        // Logique de rang basée sur le nombre de directs actifs et le volume d'équipe
        $activeDirects = $node->referrals()->where('is_active', true)->count();
        
        // Comptage total de l'équipe (descendants dans l'arbre unilevel)
        $teamCount = GenealogyNode::where('path', 'like', $node->user_id . '.%')
            ->where('is_active', true)
            ->count();

        $newRank = 'UNRANKED';

        if ($activeDirects >= 60 || ($activeDirects >= 2 && $teamCount >= 500)) {
             // Exemple pour Platinum (simplifié)
             $newRank = 'PLATINUM';
        } elseif ($activeDirects >= 30 || ($activeDirects >= 2 && $teamCount >= 100)) {
             $newRank = 'GOLD';
        } elseif ($activeDirects >= 10 || ($activeDirects >= 2 && $teamCount >= 20)) {
             $newRank = 'SILVER';
        } elseif ($activeDirects >= 2) {
             $newRank = 'BRONZE';
        }

        $node->update(['rank' => $newRank]);

        return $newRank;
    }
}

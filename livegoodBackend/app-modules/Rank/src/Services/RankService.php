<?php

namespace Modules\Rank\Services;

use Modules\Rank\Actions\DetermineBestRank;
use Modules\Rank\Models\UserRankHistory;
use Modules\Rank\Enums\RankEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RankService
{
    public function __construct(protected DetermineBestRank $determineAction) {}

    /**
     * Rafraîchit le rang d'un utilisateur et enregistre l'historique si changement.
     */
    public function refreshUserRank(string $userId): RankEnum
    {
        $user = User::findOrFail($userId);
        $stats = $this->gatherUserStats($userId);

        $newRank = $this->determineAction->execute($userId, $stats);
        $oldRankStr = $user->rank; // Supposé être dans la table users

        if ($oldRankStr !== $newRank->value) {
            DB::transaction(function() use ($user, $newRank, $oldRankStr) {
                // Mettre à jour l'utilisateur
                $user->update(['rank' => $newRank->value]);

                // Historique
                UserRankHistory::create([
                    'user_id' => $user->id,
                    'old_rank' => $oldRankStr,
                    'new_rank' => $newRank->value,
                    'metadata' => ['trigger' => 'system_refresh']
                ]);

                // Event: RankUpOccurred (pour triggering notifications)
            });
        }

        return $newRank;
    }

    /**
     * Agrégateur de données pour le moteur de rang.
     * Cette méthode doit être particulièrement optimisée.
     */
    private function gatherUserStats(string $userId): array
    {
        // Dans une vraie implémentation, on ferait des requêtes complexes 
        // ou on lirait une table de cache 'rank_counters'.
        return [
            'direct_active' => 2,
            'total_active'  => 50,
            'legs_with_bronze' => 3,
            'legs_with_silver' => 1,
            'legs_with_gold'   => 0,
            'legs_with_platinum' => 0,
        ];
    }
}

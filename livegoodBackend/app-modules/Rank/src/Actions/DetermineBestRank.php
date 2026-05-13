<?php

namespace Modules\Rank\Actions;

use Modules\Rank\Enums\RankEnum;
use Modules\Rank\Services\RequirementCheckerService;

/**
 * Evalue et retourne le meilleur rang possible pour un utilisateur.
 */
class DetermineBestRank
{
    public function __construct(protected RequirementCheckerService $checker) {}

    public function execute(string $userId, array $stats): RankEnum
    {
        $orderedRanks = [
            RankEnum::DIAMOND,
            RankEnum::PLATINUM,
            RankEnum::GOLD,
            RankEnum::SILVER,
            RankEnum::BRONZE
        ];

        foreach ($orderedRanks as $rank) {
            if ($this->checker->isEligible($userId, $rank, $stats)) {
                return $rank;
            }
        }

        return RankEnum::UNRANKED;
    }
}

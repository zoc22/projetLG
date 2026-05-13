<?php

namespace Modules\Genealogy\Actions;

use Modules\Genealogy\Services\MatrixService;
use Modules\Genealogy\Models\Matrix;
use Modules\Genealogy\Models\GenealogyNode;

/**
 * Calcul et mise à jour persistante des gains matriciels.
 */
class CalculateMatrix
{
    public function __construct(protected MatrixService $matrixService) {}

    public function execute(string $userId): void
    {
        $bonus = $this->matrixService->calculateUserBonus($userId);
        $node  = GenealogyNode::where('user_id', $userId)->first();

        if ($node) {
            Matrix::updateOrCreate(
                ['owner_id' => $userId],
                [
                    'monthly_bonus' => $bonus,
                    'max_depth'     => \Modules\Genealogy\Enums\MatrixLevelEnum::getMaxDepthForRank($node->rank)
                ]
            );
        }
    }
}

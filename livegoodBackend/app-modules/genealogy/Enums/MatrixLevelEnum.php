<?php

namespace Modules\Genealogy\Enums;

/**
 * Niveaux de la matrice (1 à 15).
 */
enum MatrixLevelEnum: int
{
    case LVL_1 = 1;
    case LVL_2 = 2;
    case LVL_3 = 3;
    case LVL_4 = 4;
    case LVL_5 = 5;
    case LVL_6 = 6;
    case LVL_7 = 7;
    case LVL_8 = 8;
    case LVL_9 = 9;
    case LVL_10 = 10;
    case LVL_11 = 11;
    case LVL_12 = 12;
    case LVL_13 = 13;
    case LVL_14 = 14;
    case LVL_15 = 15;

    /**
     * Retourne la profondeur maximale débloquée selon le rang.
     */
    public static function getMaxDepthForRank(string $rank): int
    {
        return match (strtoupper($rank)) {
            'UNRANKED' => 12,
            'BRONZE', 'SILVER' => 13,
            'GOLD', 'PLATINUM' => 14,
            'DIAMOND' => 15,
            default => 12,
        };
    }
}

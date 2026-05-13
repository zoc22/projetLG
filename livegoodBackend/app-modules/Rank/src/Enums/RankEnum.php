<?php

namespace Modules\Rank\Enums;

/**
 * Hiérarchie des rangs LiveGood.
 */
enum RankEnum: string
{
    case UNRANKED      = 'unranked';
    case BRONZE        = 'bronze';
    case SILVER        = 'silver';
    case GOLD          = 'gold';
    case PLATINUM      = 'platinum';
    case DIAMOND       = 'diamond';
    case CROWN_DIAMOND = 'crown_diamond';

    /**
     * Poids numérique pour comparaison (qui est plus haut que qui).
     */
    public function weight(): int
    {
        return match($this) {
            self::UNRANKED      => 0,
            self::BRONZE        => 1,
            self::SILVER        => 2,
            self::GOLD          => 3,
            self::PLATINUM      => 4,
            self::DIAMOND       => 5,
            self::CROWN_DIAMOND => 6,
        };
    }
}

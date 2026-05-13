<?php

namespace Modules\Affiliation\Enums;

/**
 * Rangs de qualification (Redirection ou duplication du module Rank si nécessaire).
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
}

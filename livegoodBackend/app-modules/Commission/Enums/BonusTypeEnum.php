<?php

namespace Modules\Commission\Enums;

enum BonusTypeEnum: string
{
    case FAST_START   = 'fast_start';
    case MATRIX       = 'matrix';
    case MATCHING     = 'matching';
    case RETAIL       = 'retail';
    case INFLUENCER   = 'influencer';
    case DIAMOND_POOL = 'diamond_pool';
}

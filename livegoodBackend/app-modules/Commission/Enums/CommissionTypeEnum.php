<?php

namespace Modules\Commission\Enums;

enum CommissionTypeEnum: string
{
    case WEEKLY   = 'weekly';
    case MONTHLY  = 'monthly';
    case SPECIAL  = 'special';
}

<?php

namespace Modules\Affiliation\Enums;

/**
 * Types d'abonnements LiveGood.
 */
enum SubscriptionTypeEnum: string
{
    case MONTHLY = 'monthly'; // 9.95 $
    case ANNUAL  = 'annual';  // 139.95 $
}

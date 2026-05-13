<?php

namespace Modules\Notification\Enums;

enum NotificationTypeEnum: string
{
    case WELCOME            = 'welcome';
    case COMMISSION_PAID    = 'commission_paid';
    case NEW_REFERRAL       = 'new_referral';
    case RANK_UP            = 'rank_up';
    case WITHDRAWAL_SENT    = 'withdrawal_sent';
    case SECURITY_ALERT     = 'security_alert';
}

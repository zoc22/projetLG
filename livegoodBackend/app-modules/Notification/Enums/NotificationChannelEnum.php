<?php

namespace Modules\Notification\Enums;

enum NotificationChannelEnum: string
{
    case DATABASE = 'database';
    case MAIL     = 'mail';
    case SMS      = 'sms';
}

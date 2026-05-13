<?php

namespace Modules\Notification\Services;

use Modules\Notification\Models\NotificationPreference;
use Modules\Notification\Enums\NotificationTypeEnum;
use Modules\Notification\Enums\NotificationChannelEnum;

class NotificationService
{
    public function getEligibleChannels(string $userId, NotificationTypeEnum $type): array
    {
        $prefs = NotificationPreference::where('user_id', $userId)
            ->where('notification_type', $type)
            ->first();

        if ($prefs) {
            return array_map(fn($c) => NotificationChannelEnum::from($c), $prefs->channels);
        }

        $defaults = [];
        if (config('notification.channels.database')) $defaults[] = NotificationChannelEnum::DATABASE;
        if (config('notification.channels.mail'))     $defaults[] = NotificationChannelEnum::MAIL;
        
        return $defaults;
    }

    public function logDispatch(string $userId, NotificationTypeEnum $type, NotificationChannelEnum $channel, string $content): void
    {
        \Modules\Notification\Models\NotificationLog::create([
            'user_id' => $userId,
            'type'    => $type->value,
            'channel' => $channel->value,
            'content' => $content,
            'status'  => 'sent'
        ]);
    }
}

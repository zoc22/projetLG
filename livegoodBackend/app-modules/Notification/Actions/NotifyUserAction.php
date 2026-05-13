<?php

namespace Modules\Notification\Actions;

use Modules\Notification\Services\NotificationService;
use Modules\Notification\Enums\NotificationTypeEnum;
use Modules\Notification\Enums\NotificationChannelEnum;
use Modules\Notification\Notifications\DynamicSystemNotification;
use App\Models\User;

class NotifyUserAction
{
    public function __construct(protected NotificationService $service) {}

    public function execute(string $userId, NotificationTypeEnum $type, array $data = []): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $channels = $this->service->getEligibleChannels($userId, $type);
        if (empty($channels)) return;

        $mappedChannels = array_map(fn($c) => match($c) {
            NotificationChannelEnum::DATABASE => 'database',
            NotificationChannelEnum::MAIL     => 'mail',
            NotificationChannelEnum::SMS      => 'nexmo',
        }, $channels);

        $user->notify(new DynamicSystemNotification($type, $data, $mappedChannels));

        foreach ($channels as $channel) {
            $this->service->logDispatch($userId, $type, $channel, json_encode($data));
        }
    }
}

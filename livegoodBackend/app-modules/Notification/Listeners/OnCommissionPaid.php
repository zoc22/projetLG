<?php

namespace Modules\Notification\Listeners;

use Modules\Notification\Actions\NotifyUserAction;
use Modules\Notification\Enums\NotificationTypeEnum;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnCommissionPaid implements ShouldQueue
{
    public function __construct(protected NotifyUserAction $action) {}

    public function handle($event): void
    {
        $this->action->execute(
            $event->commission->user_id,
            NotificationTypeEnum::COMMISSION_PAID,
            ['amount' => number_format($event->commission->amount, 2)]
        );
    }
}

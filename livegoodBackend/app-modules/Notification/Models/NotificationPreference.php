<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Notification\Enums\NotificationTypeEnum;

class NotificationPreference extends Model
{
    use HasUuids;
    protected $fillable = ['user_id', 'notification_type', 'channels'];
    protected $casts = [
        'notification_type' => NotificationTypeEnum::class,
        'channels'          => 'array',
    ];
}

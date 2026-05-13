<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NotificationLog extends Model
{
    use HasUuids;
    protected $fillable = ['user_id', 'type', 'channel', 'content', 'status', 'error_message'];
}

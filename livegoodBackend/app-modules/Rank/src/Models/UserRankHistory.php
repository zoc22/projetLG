<?php

namespace Modules\Rank\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserRankHistory extends Model
{
    use HasUuids;
    protected $fillable = ['user_id', 'old_rank', 'new_rank', 'metadata'];
    protected $casts = ['metadata' => 'array'];
}

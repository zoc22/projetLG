<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatrixBonus extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'active_members_count'];
}

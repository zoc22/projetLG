<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FastStartBonus extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'downline_id', 'level', 'percentage'];
}

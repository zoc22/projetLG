<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InfluencerBonus extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'monthly_sales_volume', 'extra_percentage'];
}

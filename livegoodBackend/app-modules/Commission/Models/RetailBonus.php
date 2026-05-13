<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RetailBonus extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'order_id', 'price_difference'];
}

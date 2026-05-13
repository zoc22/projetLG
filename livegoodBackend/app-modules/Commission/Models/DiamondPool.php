<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DiamondPool extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'total_company_revenue', 'eligible_diamonds_count'];
}

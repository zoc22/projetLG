<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MatchingBonus extends Model
{
    use HasUuids;
    protected $fillable = ['commission_id', 'source_affiliate_id', 'source_matrix_amount', 'match_percentage'];
}

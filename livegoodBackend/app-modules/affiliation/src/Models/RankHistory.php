<?php

namespace Modules\Affiliation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;

/**
 * Historique des passages de rang (Audit Trail).
 */
class RankHistory extends Model
{
    use HasUuid;

    protected $fillable = ['affiliate_id', 'old_rank', 'new_rank', 'reason_metadata'];

    protected $casts = [
        'reason_metadata' => 'array'
    ];
}

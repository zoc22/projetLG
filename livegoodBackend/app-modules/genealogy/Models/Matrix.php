<?php

namespace Modules\Genealogy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Agrégation des données de la matrice pour un utilisateur donné.
 */
class Matrix extends Model
{
    use HasUuids;

    protected $fillable = [
        'owner_id',
        'max_depth',
        'total_members',
        'monthly_bonus'
    ];

    /**
     * Propriétaire de cette vue matricielle.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(GenealogyNode::class, 'owner_id', 'user_id');
    }
}

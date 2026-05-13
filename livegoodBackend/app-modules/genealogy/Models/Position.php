<?php

namespace Modules\Genealogy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Représente un emplacement dans la matrice forcée 2x15 (Powerline).
 */
class Position extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'parent_id',
        'placer_id',      // Qui a causé le placement (sponsor ou débordement)
        'level',          // Niveau absolu dans la matrice globale
        'position_index', // Index horizontal (0 ou 1 pour binaire)
        'side',           // 'left' ou 'right'
        'matrix_path'     // Chemin hiérarchique dans la matrice pour optimiser les requêtes
    ];

    /**
     * Occupant de cette position.
     */
    public function occupant(): BelongsTo
    {
        return $this->belongsTo(GenealogyNode::class, 'user_id', 'user_id');
    }

    /**
     * Emplacement parent direct.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Emplacements enfants (maximum 2 : gauche et droite).
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Scope pour filtrer les positions sous un certain chemin matriciel.
     */
    public function scopeUnderPath($query, $path)
    {
        return $query->where('matrix_path', 'like', $path . '.%');
    }
}

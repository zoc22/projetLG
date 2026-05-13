<?php

namespace Modules\Genealogy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

/**
 * Représente un nœud dans l'arbre de parrainage direct (Unilevel).
 */
class GenealogyNode extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'sponsor_id',
        'path', // Format: sponsor_uuid.direct_sponsor_uuid.user_uuid
        'depth',
        'rank',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Utilisateur associé au nœud.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sponsor direct du membre.
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'sponsor_id');
    }

    /**
     * Filleuls directs (Enrolment Tree).
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'sponsor_id');
    }

    /**
     * Récupère tous les ancêtres (up-line) optimisé par le chemin (path).
     */
    public function getAncestors()
    {
        if (empty($this->path)) return collect();

        $ids = explode('.', $this->path);
        array_pop($ids); // Exclure l'utilisateur actuel

        return self::whereIn('user_id', $ids)
            ->orderBy('depth', 'desc')
            ->get();
    }
}

<?php

namespace Modules\Affiliation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Modules\Affiliation\Enums\RankEnum;
use Modules\Affiliation\Enums\AffiliationStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Affiliate : Cœur business lié à un utilisateur.
 * Gère le statut, le rang et le solde des commissions.
 */
class Affiliate extends Model
{
    use HasUuid;

    protected $fillable = [
        'user_id', 'username_canonical', 'code_affiliation', 
        'status', 'rank', 'total_commissions', 'pending_balance'
    ];

    protected $casts = [
        'status' => AffiliationStatusEnum::class,
        'rank'   => RankEnum::class,
    ];

    /**
     * Utilisateur parent.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Modules\Authentication\Models\User');
    }

    /**
     * Liens d'affiliation personnalisés.
     */
    public function links(): HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    /**
     * Historique des rangs.
     */
    public function rankHistory(): HasMany
    {
        return $this->hasMany(RankHistory::class);
    }
}

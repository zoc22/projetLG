<?php

namespace Modules\Statistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Statistics\Enums\VisitSourceEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant un log de visite unique sur un lien d'affiliation.
 */
class SiteVisit extends Model
{
    use HasUuids;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'affiliate_id',
        'site_type',
        'ip_address',
        'user_agent',
        'source',
        'country_code'
    ];

    /**
     * Conversion automatique des types.
     */
    protected $casts = [
        'source' => VisitSourceEnum::class,
    ];

    /**
     * Relation vers l'affilié (User).
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'affiliate_id');
    }
}

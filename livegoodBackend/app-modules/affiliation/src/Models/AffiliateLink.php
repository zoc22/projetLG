<?php

namespace Modules\Affiliation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle AffiliateLink : Lien de redirection tracké.
 */
class AffiliateLink extends Model
{
    use HasUuid;

    protected $fillable = ['affiliate_id', 'name', 'slug', 'target_type', 'clicks_count'];

    /**
     * L'affilié qui possède ce lien.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Génère l'URL publique absolue.
     */
    public function getFullUrlAttribute(): string
    {
        return url("/r/{$this->slug}");
    }
}

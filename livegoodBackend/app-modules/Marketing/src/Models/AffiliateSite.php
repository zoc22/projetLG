<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Marketing\Enums\SiteTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour un site d'affiliation personnalisé.
 */
class AffiliateSite extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'type', 'slug', 'custom_domain', 'visits_count', 'leads_count', 'sales_count'];

    protected $casts = [
        'type' => SiteTypeEnum::class,
    ];

    /**
     * L'affilié propriétaire.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Leads capturés par ce site.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'site_id');
    }
}

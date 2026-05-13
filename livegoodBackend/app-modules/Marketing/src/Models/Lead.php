<?php

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Marketing\Enums\LeadStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour une pré-inscription (Lead).
 */
class Lead extends Model
{
    use HasUuids;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'affiliate_id', 'site_id', 
        'preenrolled_at', 'cutoff_at', 'status', 'converted_user_id'
    ];

    protected $casts = [
        'status'         => LeadStatusEnum::class,
        'preenrolled_at' => 'datetime',
        'cutoff_at'      => 'datetime',
    ];

    /**
     * L'affilié qui a généré ce lead.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'affiliate_id');
    }

    /**
     * Site spécifique d'origine.
     */
    public function originSite(): BelongsTo
    {
        return $this->belongsTo(AffiliateSite::class, 'site_id');
    }

    /**
     * Vérifie si le lead a expiré son temps de réservation.
     */
    public function isExpired(): bool
    {
        return $this->status === LeadStatusEnum::EXPIRED || (now()->greaterThan($this->cutoff_at));
    }
}

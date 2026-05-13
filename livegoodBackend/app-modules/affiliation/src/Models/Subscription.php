<?php

namespace Modules\Affiliation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Subscription : Gère l'abonnement récurrent.
 */
class Subscription extends Model
{
    use HasUuid;

    protected $fillable = [
        'user_id', 'type', 'status', 'starts_at', 
        'ends_at', 'last_payment_at', 'next_billing_at', 'auto_renew'
    ];

    protected $casts = [
        'starts_at'       => 'datetime',
        'ends_at'         => 'datetime',
        'last_payment_at' => 'datetime',
        'next_billing_at' => 'datetime',
        'auto_renew'      => 'boolean'
    ];

    /**
     * Propriétaire de l'abonnement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Modules\Authentication\Models\User');
    }

    /**
     * Vérifie si l'abonnement est actif à l'instant T.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && now()->lessThan($this->ends_at);
    }
}

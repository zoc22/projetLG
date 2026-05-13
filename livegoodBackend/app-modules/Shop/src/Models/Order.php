<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour une commande client ou membre.
 */
class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'affiliate_id', 'ordered_at', 'status', 
        'total_amount', 'shipping_method', 'shipping_address'
    ];

    protected $casts = [
        'ordered_at'   => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    /**
     * L'acheteur.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * L'affilié parrain (si vente au détail).
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'affiliate_id');
    }

    /**
     * Lignes d'articles.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

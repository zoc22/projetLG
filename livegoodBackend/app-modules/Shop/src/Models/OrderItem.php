<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ligne de détail d'une commande.
 */
class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'discount'];

    /**
     * Calcule le sous-total de la ligne.
     */
    public function getSubTotal(): float
    {
        return (float) (($this->unit_price - $this->discount) * $this->quantity);
    }

    /**
     * Le produit lié.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

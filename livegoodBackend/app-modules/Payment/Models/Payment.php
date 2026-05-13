<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Payment\Enums\PaymentStatusEnum;
use Modules\Payment\Enums\PaymentMethodEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle de transaction financière.
 */
class Payment extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'amount', 'currency', 'method', 
        'status', 'reference', 'description', 'metadata'
    ];

    protected $casts = [
        'status'   => PaymentStatusEnum::class,
        'method'   => PaymentMethodEnum::class,
        'metadata' => 'array'
    ];

    /**
     * Utilisateur bénéficiaire ou émetteur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}

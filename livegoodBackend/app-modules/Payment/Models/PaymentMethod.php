<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Payment\Enums\PaymentMethodEnum;

/**
 * Configuration d'un moyen de paiement enregistré par l'utilisateur.
 */
class PaymentMethod extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'type', 'details', 'is_default'
    ];

    protected $casts = [
        'type' => PaymentMethodEnum::class,
        'details' => 'array',
        'is_default' => 'boolean'
    ];
}

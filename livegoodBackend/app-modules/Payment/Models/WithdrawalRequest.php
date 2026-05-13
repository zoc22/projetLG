<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payment\Enums\WithdrawalStatusEnum;

/**
 * Requête de retrait initiée par un affilié.
 */
class WithdrawalRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'payment_method_id', 'amount', 
        'fees', 'net_amount', 'status', 
        'rejection_reason', 'processed_at'
    ];

    protected $casts = [
        'status'       => WithdrawalStatusEnum::class,
        'processed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}

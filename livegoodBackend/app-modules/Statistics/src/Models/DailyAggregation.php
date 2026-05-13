<?php

namespace Modules\Statistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle stockant les métriques pré-calculées par jour et par utilisateur.
 * Optimise drastiquement les performances du dashboard.
 */
class DailyAggregation extends Model
{
    use HasUuids;

    protected $fillable = [
        'affiliate_id',
        'reference_date',
        'visits_count',
        'preinscriptions_count',
        'conversions_count',
        'revenue_amount'
    ];

    protected $casts = [
        'reference_date' => 'date',
        'revenue_amount' => 'decimal:2'
    ];

    /**
     * Utilisateur concerné.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'affiliate_id');
    }
}

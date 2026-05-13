<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Modules\Training\Enums\TrainingLevelEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Training : Un cours structuré.
 */
class Training extends Model
{
    use HasUuid;

    protected $fillable = [
        'trainer_id', 'title', 'description', 'level', 
        'video_url', 'duration_minutes', 'is_certifying', 'prerequisites'
    ];

    protected $casts = [
        'level'         => TrainingLevelEnum::class,
        'prerequisites' => 'array',
        'is_certifying' => 'boolean'
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}

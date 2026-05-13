<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Webinar : Conférence en direct ou Replay.
 */
class Webinar extends Model
{
    use HasUuid;

    protected $fillable = ['trainer_id', 'title', 'description', 'scheduled_at', 'language', 'zoom_link', 'replay_url'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}

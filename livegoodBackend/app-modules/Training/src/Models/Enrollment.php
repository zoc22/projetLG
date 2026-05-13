<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Enrollment : Inscription et progression d'un membre.
 */
class Enrollment extends Model
{
    use HasUuid;

    protected $table = 'training_enrollments';
    protected $fillable = ['user_id', 'training_id', 'progress_percentage', 'completed_at', 'certificate_path'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}

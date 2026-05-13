<?php

namespace Modules\Training\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Trainer : L'instructeur de la formation.
 */
class Trainer extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'role', 'bio', 'avatar_url'];

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    public function webinars(): HasMany
    {
        return $this->hasMany(Webinar::class);
    }
}

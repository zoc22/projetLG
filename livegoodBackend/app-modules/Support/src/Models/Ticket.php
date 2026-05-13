<?php

namespace Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Modules\Support\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Ticket : Représente une demande d'assistance.
 */
class Ticket extends Model
{
    use HasUuid;

    protected $fillable = ['user_id', 'subject', 'status', 'priority'];

    protected $casts = [
        'status' => TicketStatusEnum::class,
    ];

    /**
     * L'auteur du ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('Modules\Authentication\Models\User');
    }

    /**
     * Fil de discussion.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Vérifie si le ticket peut encore recevoir des réponses.
     */
    public function isOpen(): bool
    {
        return $this->status !== TicketStatusEnum::CLOSED;
    }
}

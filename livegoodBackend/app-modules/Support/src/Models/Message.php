<?php

namespace Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Message : Un message dans une discussion de support.
 */
class Message extends Model
{
    use HasUuid;

    protected $table = 'ticket_messages';
    protected $fillable = ['ticket_id', 'user_id', 'content', 'attachments'];

    protected $casts = [
        'attachments' => 'array'
    ];

    /**
     * Le ticket parent.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * L'auteur du message.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo('Modules\Authentication\Models\User', 'user_id');
    }
}

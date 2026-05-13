<?php

namespace Modules\Support\Services;

use Modules\Support\Models\Ticket;
use Modules\Support\Models\Message;
use Modules\Support\Enums\TicketStatusEnum;
use Illuminate\Support\Facades\DB;

/**
 * Service métier pour la gestion du support technique.
 */
class SupportService
{
    /**
     * Ouvre un nouveau ticket avec un message initial.
     */
    public function openTicket(string $userId, array $data): Ticket
    {
        return DB::transaction(function() use ($userId, $data) {
            $ticket = Ticket::create([
                'user_id'  => $userId,
                'subject'  => $data['subject'],
                'priority' => $data['priority'] ?? 'medium',
                'status'   => TicketStatusEnum::OPEN
            ]);

            $this->addMessage($ticket->id, $userId, $data['message']);

            return $ticket;
        });
    }

    /**
     * Ajoute une réponse à un ticket existant.
     */
    public function addMessage(string $ticketId, string $userId, string $content): Message
    {
        $ticket = Ticket::findOrFail($ticketId);

        if (!$ticket->isOpen()) {
            throw new \Exception("Cannot reply to a closed ticket.");
        }

        // Si c'est un message d'un admin, on passe en "En cours"
        $author = \Modules\Authentication\Models\User::find($userId);
        if ($author->isAdmin() && $ticket->status === TicketStatusEnum::OPEN) {
            $ticket->update(['status' => TicketStatusEnum::IN_PROGRESS]);
        }

        return Message::create([
            'ticket_id' => $ticketId,
            'user_id'   => $userId,
            'content'   => $content
        ]);
    }

    /**
     * Ferme le ticket (Terminal).
     */
    public function closeTicket(string $ticketId): void
    {
        Ticket::where('id', $ticketId)->update(['status' => TicketStatusEnum::CLOSED]);
    }
}

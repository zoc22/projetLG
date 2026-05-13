<?php
declare(strict_types=1);

namespace Modules\Authentication\Events;

use Modules\Authentication\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event UserLoggedOut
 *
 * Dispatched lorsqu'un utilisateur se déconnecte.
 * Utile pour le nettoyage de cache ou les audits.
 *
 * @package Modules\Authentication\Events
 */
class UserLoggedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * L'utilisateur déconnecté
     *
     * @var User
     */
    public User $user;

    /**
     * Timestamp de déconnexion
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $logoutAt;

    /**
     * Constructeur
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->logoutAt = now();
    }
}
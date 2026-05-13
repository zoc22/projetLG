<?php
declare(strict_types=1);

namespace Modules\Authentication\Events;

use Modules\Authentication\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event UserLoggedIn
 *
 * Dispatched lorsqu'un utilisateur se connecte avec succès.
 * Permet aux listeners d'exécuter des actions post-connexion.
 *
 * @package Modules\Authentication\Events
 */
class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * L'utilisateur connecté
     *
     * @var User
     */
    public User $user;

    /**
     * Adresse IP de la connexion
     *
     * @var string|null
     */
    public ?string $ipAddress;

    /**
     * User agent du navigateur
     *
     * @var string|null
     */
    public ?string $userAgent;

    /**
     * Indique si l'utilisateur a choisi "Se souvenir de moi"
     *
     * @var bool
     */
    public bool $remember;

    /**
     * Constructeur
     *
     * @param User $user
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @param bool $remember
     */
    public function __construct(
        User $user,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        bool $remember = false
    ) {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->remember = $remember;
    }
}
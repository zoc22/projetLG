<?php
declare(strict_types=1);

namespace Modules\Authentication\Events;

use Modules\Authentication\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event UserRegistered
 *
 * Dispatched lorsqu'un nouvel utilisateur s'inscrit.
 * Permet d'envoyer des emails, créer des structures par défaut,
 * et notifier les administrateurs.
 *
 * @package Modules\Authentication\Events
 */
class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Le nouvel utilisateur
     *
     * @var User
     */
    public User $user;

    /**
     * Code de parrainage (si fourni)
     *
     * @var string|null
     */
    public ?string $referralCode;

    /**
     * Timestamp d'inscription
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $registeredAt;

    /**
     * Constructeur
     *
     * @param User $user
     * @param string|null $referralCode
     */
    public function __construct(User $user, ?string $referralCode = null)
    {
        $this->user = $user;
        $this->referralCode = $referralCode;
        $this->registeredAt = now();
    }

    /**
     * Vérifie si l'inscription vient d'un parrainage
     *
     * @return bool
     */
    public function hasReferral(): bool
    {
        return !empty($this->referralCode);
    }
}
<?php
declare(strict_types=1);

namespace Modules\Authentication\Listeners;

use Modules\Authentication\Events\UserLoggedIn;
use Modules\Authentication\Events\UserLoggedOut;
use Modules\Authentication\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

/**
 * Listener LogAuthenticationActivity
 *
 * Journalise toutes les activités d'authentification.
 * Permet l'audit et la détection d'activités suspectes.
 *
 * @package Modules\Authentication\Listeners
 */
class LogAuthenticationActivity
{
    /**
     * Traite les événements d'authentification
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        if (!config('authentication.events.log_all_auth_events', true)) {
            return;
        }

        $logData = $this->getLogData($event);
        
        Log::channel('auth')->info($this->getLogMessage($event), $logData);
    }

    /**
     * Obtient le message de log selon l'événement
     *
     * @param object $event
     * @return string
     */
    private function getLogMessage(object $event): string
    {
        return match($event::class) {
            UserLoggedIn::class => 'Utilisateur connecté',
            UserLoggedOut::class => 'Utilisateur déconnecté',
            UserRegistered::class => 'Nouvel utilisateur inscrit',
            default => 'Activité d\'authentification',
        };
    }

    /**
     * Obtient les données de log selon l'événement
     *
     * @param object $event
     * @return array
     */
    private function getLogData(object $event): array
    {
        return match($event::class) {
            UserLoggedIn::class => [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'ip' => $event->ipAddress,
                'user_agent' => $event->userAgent,
                'remember' => $event->remember,
                'timestamp' => now()->toIso8601String(),
            ],
            UserLoggedOut::class => [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'logout_at' => $event->logoutAt->toIso8601String(),
                'ip' => request()->ip(),
            ],
            UserRegistered::class => [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'referral_code' => $event->referralCode,
                'has_referral' => $event->hasReferral(),
                'ip' => request()->ip(),
                'timestamp' => $event->registeredAt->toIso8601String(),
            ],
            default => [],
        };
    }
}
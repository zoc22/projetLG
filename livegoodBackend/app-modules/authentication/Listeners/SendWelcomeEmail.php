<?php
declare(strict_types=1);

namespace Modules\Authentication\Listeners;

use Modules\Authentication\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Listener SendWelcomeEmail
 *
 * Envoie un email de bienvenue aux nouveaux utilisateurs.
 * Inclut les informations de connexion et les liens utiles.
 *
 * @package Modules\Authentication\Listeners
 */
class SendWelcomeEmail
{
    /**
     * Traite l'événement
     *
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event): void
    {
        try {
            $user = $event->user;
            
            // Vérifier si l'envoi d'email est activé
            if (!config('authentication.events.send_welcome_email', true)) {
                return;
            }

            // Envoyer l'email de bienvenue
            Mail::send('authentication::emails.welcome', [
                'user' => $user,
                'verificationUrl' => $this->getVerificationUrl($user),
                'dashboardUrl' => config('app.url') . '/dashboard',
                'supportEmail' => config('mail.support_email', 'support@livegood.com'),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->getFullNameAttribute())
                    ->subject('Bienvenue chez LiveGood !')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Email de bienvenue envoyé', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de bienvenue', [
                'user_id' => $event->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Obtient l'URL de vérification
     *
     * @param User $user
     * @return string
     */
    private function getVerificationUrl(User $user): string
    {
        return config('app.url') . '/api/auth/verify-email/' . $user->id . '/' . sha1($user->email);
    }
}
<?php

namespace Modules\Genealogy\Listeners;

use Modules\Authentication\Events\UserRegistered;
use Modules\Genealogy\Actions\AddToGenealogy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleUserRegistration
{
    /**
     * Create the event listener.
     */
    public function __construct(protected AddToGenealogy $addToGenealogy)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Log::info('Handling UserRegistered in Genealogy module', [
            'user_id' => $event->user->id,
            'referral_code' => $event->referralCode
        ]);

        try {
            // Le referralCode est optionnel. S'il est présent, on cherche le sponsor.
            // Note: On suppose ici que le referralCode est l'ID de l'utilisateur ou un code unique lié.
            // Pour LiveGood, on va simplifier : si c'est un UUID, c'est l'ID du sponsor.
            $sponsorId = $event->referralCode;
            
            // Si le sponsorId n'existe pas dans la table users, on ignore (ou on met à null)
            if ($sponsorId && !\DB::table('users')->where('id', $sponsorId)->exists()) {
                Log::warning('Referral code provided but user not found', ['code' => $sponsorId]);
                $sponsorId = null;
            }

            $this->addToGenealogy->execute($event->user->id, $sponsorId);
            
            Log::info('User successfully added to genealogy', ['user_id' => $event->user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to add user to genealogy', [
                'user_id' => $event->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}

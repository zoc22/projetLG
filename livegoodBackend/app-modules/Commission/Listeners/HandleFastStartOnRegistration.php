<?php

namespace Modules\Commission\Listeners;

use Modules\Authentication\Events\UserRegistered;
use Modules\Commission\Services\FastStartService;
use Illuminate\Support\Facades\Log;

class HandleFastStartOnRegistration
{
    /**
     * Create the event listener.
     */
    public function __construct(protected FastStartService $fastStartService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Log::info('Handling Fast Start for new registration', [
            'user_id' => $event->user->id,
            'referral_code' => $event->referralCode
        ]);

        try {
            $sponsorId = $event->referralCode;
            
            // On ne calcule le Fast Start que s'il y a un parrain valide
            if ($sponsorId && \DB::table('users')->where('id', $sponsorId)->exists()) {
                $this->fastStartService->processNewEnrollment($event->user->id, $sponsorId);
                Log::info('Fast Start processed for registration', ['user_id' => $event->user->id, 'sponsor_id' => $sponsorId]);
            } else {
                Log::info('No valid sponsor for Fast Start', ['user_id' => $event->user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process Fast Start', [
                'user_id' => $event->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}

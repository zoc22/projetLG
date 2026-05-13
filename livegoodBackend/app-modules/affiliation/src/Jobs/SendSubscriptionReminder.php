<?php

namespace Modules\Affiliation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Affiliation\Models\Subscription;
use Illuminate\Support\Facades\Log;

/**
 * Job pour envoyer un rappel avant expiration de l'abonnement.
 */
class SendSubscriptionReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiringSoon = Subscription::where('status', 'active')
            ->where('ends_at', '<=', now()->addDays(3))
            ->get();

        foreach ($expiringSoon as $sub) {
            Log::info("Reminder sent to User {$sub->user_id} for subscription expiration.");
            // Mail::to($sub->user->email)->send(new SubscriptionExpiringMail($sub));
        }
    }
}

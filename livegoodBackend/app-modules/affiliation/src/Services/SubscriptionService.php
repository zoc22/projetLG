<?php

namespace Modules\Affiliation\Services;

use Modules\Affiliation\Models\Subscription;
use Modules\Affiliation\Enums\SubscriptionTypeEnum;
use Carbon\Carbon;

/**
 * Service pour la gestion du cycle de vie des abonnements.
 */
class SubscriptionService
{
    /**
     * Souscrit un utilisateur à un plan.
     */
    public function subscribe(string $userId, SubscriptionTypeEnum $type): Subscription
    {
        $duration = $type === SubscriptionTypeEnum::ANNUAL ? 12 : 1;
        
        return Subscription::create([
            'user_id'         => $userId,
            'type'            => $type->value,
            'status'          => 'active',
            'starts_at'       => now(),
            'ends_at'         => now()->addMonths($duration),
            'last_payment_at' => now(),
            'next_billing_at' => now()->addMonths($duration)->subDays(2),
            'auto_renew'      => true
        ]);
    }

    /**
     * Vérifie la validité de l'abonnement.
     */
    public function checkStatus(string $userId): bool
    {
        $sub = Subscription::where('user_id', $userId)->where('status', 'active')->latest()->first();
        return $sub && $sub->isActive();
    }
}

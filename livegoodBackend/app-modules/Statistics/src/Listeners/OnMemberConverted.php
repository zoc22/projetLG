<?php

namespace Modules\Statistics\Listeners;

use Modules\Statistics\Services\ConversionService;

/**
 * Ecouteur déclenché quand un lead devient membre payant (Vente).
 */
class OnMemberConverted
{
    public function __construct(protected ConversionService $service) {}

    public function handle($event): void
    {
        // On récupère le parrain qui a réalisé la vente
        $affiliateId = $event->member->parrainId;
        $orderAmount = $event->order->amount ?? 0;

        $this->service->recordSale($affiliateId, $orderAmount);
    }
}

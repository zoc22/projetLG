<?php

namespace Modules\Statistics\Listeners;

use Modules\Statistics\Services\ConversionService;

/**
 * Ecouteur déclenché quand une nouvelle pré-inscription arrive (Lead).
 */
class OnPreinscriptionCreated
{
    public function __construct(protected ConversionService $service) {}

    public function handle($event): void
    {
        // Supposons que l'event porte 'affiliate_source_id'
        if (isset($event->preinscription->affilieSourceId)) {
            $this->service->recordLead($event->preinscription->affilieSourceId);
        }
    }
}

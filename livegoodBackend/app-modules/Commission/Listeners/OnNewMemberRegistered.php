<?php

namespace Modules\Commission\Listeners;

use Modules\Commission\Services\FastStartService;

class OnNewMemberRegistered
{
    public function __construct(protected FastStartService $service) {}

    /**
     * Cet évènement est supposé provenir du module Genealogy ou User.
     */
    public function handle($event): void
    {
        // $event->user, $event->sponsor
        $this->service->processNewEnrollment($event->user->id, $event->sponsor->id);
    }
}

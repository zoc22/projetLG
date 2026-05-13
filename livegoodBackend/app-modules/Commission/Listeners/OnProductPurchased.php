<?php

namespace Modules\Commission\Listeners;

use Modules\Commission\Actions\CalculateRetailBonus;

class OnProductPurchased
{
    public function __construct(protected CalculateRetailBonus $action) {}

    public function handle($event): void
    {
        // On calcule le retail bonus si l'acheteur n'est pas membre ou achète au prix public
        if ($event->order->type === 'retail') {
            $this->action->execute(
                $event->sponsor->id, 
                $event->order->id, 
                $event->order->price_difference, 
                now()->format('Y-m-d')
            );
        }
    }
}

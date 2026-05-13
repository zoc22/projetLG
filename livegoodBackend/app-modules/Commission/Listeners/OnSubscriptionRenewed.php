<?php

namespace Modules\Commission\Listeners;

use Modules\Commission\Actions\CalculateMatrixBonus;

class OnSubscriptionRenewed
{
    public function handle($event): void
    {
        // Le renouvellement mensuel de 9.95$ nourrit la matrice.
        // On pourrait marquer l'utilisateur comme actif pour le prochain calcul global.
    }
}

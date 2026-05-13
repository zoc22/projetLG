<?php

namespace Modules\Genealogy\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Genealogy\Models\Position;

/**
 * Déclenché lorsqu'un membre est placé avec succès dans la matrice.
 */
class MemberPlacedInMatrix
{
    use Dispatchable, SerializesModels;

    public function __construct(public Position $position) {}
}

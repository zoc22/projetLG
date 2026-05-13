<?php

namespace Modules\Statistics\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour une ligne de série temporelle (Graphiques).
 */
class VisitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'date'            => $this->reference_date->toDateString(),
            'visits'          => (int) $this->visits_count,
            'preinscriptions' => (int) $this->preinscriptions_count,
            'conversions'     => (int) $this->conversions_count,
            'revenue'         => (float) $this->revenue_amount,
        ];
    }
}

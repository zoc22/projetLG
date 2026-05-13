<?php

namespace Modules\Statistics\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour transformer le résumé statistique en format propre JSON.
 */
class StatSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'conversion_rate' => (float) $this['conversion_rate'],
            'activation_rate' => (float) $this['activation_rate'],
            'weekly_growth'   => (float) $this['weekly_growth'],
            'is_positive'     => $this['weekly_growth'] > 0
        ];
    }
}

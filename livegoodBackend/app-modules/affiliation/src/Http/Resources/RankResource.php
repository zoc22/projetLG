<?php

namespace Modules\Affiliation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'rank' => $this->rank,
            'label' => ucfirst($this->rank->value),
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}

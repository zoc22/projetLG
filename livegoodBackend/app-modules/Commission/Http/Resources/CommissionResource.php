<?php

namespace Modules\Commission\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'amount'        => (float) $this->amount,
            'status'        => $this->status,
            'type'          => $this->type,
            'period'        => $this->period_string,
            'date'          => $this->created_at->toDateString(),
            'bonus_details' => $this->getBonusDetails(),
        ];
    }

    private function getBonusDetails(): ?array
    {
        if ($this->fastStart) return ['type' => 'Fast Start', 'level' => $this->fastStart->level];
        if ($this->matrix)    return ['type' => 'Matrix', 'members' => $this->matrix->active_members_count];
        return null;
    }
}

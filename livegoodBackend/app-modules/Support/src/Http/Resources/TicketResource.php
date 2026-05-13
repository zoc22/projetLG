<?php

namespace Modules\Support\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'subject'    => $this->subject,
            'status'     => $this->status,
            'priority'   => $this->priority,
            'created_at' => $this->created_at->toDateTimeString(),
            'messages'   => $this->whenLoaded('messages'),
        ];
    }
}

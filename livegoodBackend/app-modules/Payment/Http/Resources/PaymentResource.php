<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'amount'      => (float) $this->amount,
            'currency'    => $this->currency,
            'method'      => $this->method,
            'status'      => $this->status,
            'reference'   => $this->reference,
            'description' => $this->description,
            'date'        => $this->created_at->toDateTimeString(),
        ];
    }
}

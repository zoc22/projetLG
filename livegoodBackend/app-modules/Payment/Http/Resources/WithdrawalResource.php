<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'requested_at' => $this->created_at->toDateTimeString(),
            'amount'       => (float) $this->amount,
            'fees'         => (float) $this->fees,
            'net_amount'   => (float) $this->net_amount,
            'status'       => $this->status,
            'method'       => new \Modules\Payment\Http\Resources\PaymentMethodResource($this->method),
            'processed_at' => $this->processed_at?->toDateTimeString(),
        ];
    }
}

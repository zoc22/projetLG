<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'is_default' => $this->is_default,
            // On ne retourne pas les détails sensibles bruts
            'label'      => $this->getHumanReadableLabel(),
        ];
    }

    private function getHumanReadableLabel(): string
    {
        return match($this->type->value) {
            'crypto'    => 'Wallet: ' . substr($this->details['address'] ?? '', 0, 8) . '...',
            'bank_wire' => 'Bank: ' . ($this->details['bank_name'] ?? 'Unknown'),
            default     => $this->type->value
        };
    }
}

<?php

namespace Modules\Affiliation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour l'affilié.
 */
class AffiliateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'pseudo'             => $this->username_canonical,
            'referral_code'      => $this->referral_code,
            'status'             => $this->status,
            'rank'               => $this->rank,
            'earnings'           => (float) $this->total_commissions,
            'pending'            => (float) $this->pending_balance,
            'joined_at'          => $this->created_at->toDateTimeString(),
        ];
    }
}

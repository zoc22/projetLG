<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource UserResource
 *
 * Transforme le modèle User pour les réponses API.
 * Cache les champs sensibles et formate les dates.
 *
 * @package Modules\Authentication\Http\Resources
 */
class UserResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'full_name' => $this->getFullNameAttribute(),
            'email' => $this->email,
            'email_verified' => !is_null($this->email_verified_at),
            'pays' => $this->pays,
            'devise' => $this->devise,
            'role' => [
                'value' => $this->type_utilisateur->value,
                'label' => $this->type_utilisateur->getLabel(),
                'access_level' => $this->type_utilisateur->getAccessLevel(),
            ],
            'status' => [
                'value' => $this->statut_compte->value,
                'label' => $this->statut_compte->getLabel(),
                'color' => $this->statut_compte->getColor(),
                'can_login' => $this->statut_compte->canLogin(),
            ],
            'subscription' => $this->whenLoaded('subscription', function () {
                return [
                    'type' => $this->subscription?->type,
                    'montant' => $this->subscription?->montant,
                    'date_fin' => $this->subscription?->date_fin?->format('Y-m-d'),
                    'is_active' => $this->subscription?->isActive() ?? false,
                ];
            }),
            'affiliate' => $this->whenLoaded('affiliate', function () {
                return [
                    'id' => $this->affiliate?->id,
                    'pseudo' => $this->affiliate?->pseudo,
                    'code_affiliation' => $this->affiliate?->code_affiliation,
                    'rang' => $this->affiliate?->rang_actuel?->value,
                    'total_gains' => $this->affiliate?->total_gains,
                ];
            }),
            'dates' => [
                'inscription' => $this->date_inscription?->format('Y-m-d H:i:s'),
                'last_login' => $this->last_login_at?->format('Y-m-d H:i:s'),
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            ],
            'has_two_factor' => !is_null($this->two_factor_secret),
        ];
    }
}
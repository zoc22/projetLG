<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource AuthResource
 *
 * Transforme la réponse d'authentification pour les réponses API.
 * Inclut le token, l'utilisateur et les informations de session.
 *
 * @package Modules\Authentication\Http\Resources
 */
class AuthResource extends JsonResource
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
            'success' => $this->resource['success'] ?? true,
            'message' => $this->resource['message'] ?? 'Authentification réussie.',
            'data' => [
                'user' => new UserResource($this->resource['user'] ?? null),
                'access_token' => $this->resource['token'] ?? null,
                'token_type' => 'Bearer',
                'expires_in' => config('authentication.jwt.ttl', 120) * 60,
                'requires_2fa' => $this->resource['requires_2fa'] ?? false,
            ],
        ];
    }
}
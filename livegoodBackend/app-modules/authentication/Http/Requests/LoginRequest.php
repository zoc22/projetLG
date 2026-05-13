<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request LoginRequest
 *
 * Valide les données de connexion.
 *
 * @package Modules\Authentication\Http\Requests
 */
class LoginRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Messages de validation personnalisés
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'password.required' => 'Le mot de passe est requis.',
        ];
    }
}
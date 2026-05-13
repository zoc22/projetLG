<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request ResetPasswordRequest
 *
 * Valide les données de réinitialisation du mot de passe.
 *
 * @package Modules\Authentication\Http\Requests
 */
class ResetPasswordRequest extends FormRequest
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
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
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
            'token.required' => 'Le token de réinitialisation est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.exists' => 'Aucun compte trouvé avec cet email.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
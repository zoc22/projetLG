<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request ChangePasswordRequest
 *
 * Valide le changement de mot de passe par un utilisateur connecté.
 *
 * @package Modules\Authentication\Http\Requests
 */
class ChangePasswordRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // L'authentification est gérée par le middleware
    }

    /**
     * Règles de validation
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => [
                'required',
                'confirmed',
                'different:current_password',
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
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'new_password.required' => 'Le nouveau mot de passe est requis.',
            'new_password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
            'new_password.different' => 'Le nouveau mot de passe doit être différent du mot de passe actuel.',
        ];
    }
}
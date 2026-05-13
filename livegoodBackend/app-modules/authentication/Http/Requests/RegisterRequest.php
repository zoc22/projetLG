<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request RegisterRequest
 *
 * Valide les données d'inscription.
 *
 * @package Modules\Authentication\Http\Requests
 */
class RegisterRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'prenom' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required', 'string'],
            'pays' => ['nullable', 'string', 'max:100'],
            'devise' => ['nullable', 'string', 'in:USD,EUR,GBP,CAD'],
            'parrain_code' => ['nullable', 'string', 'exists:affiliates,code_affiliation'],
            'role' => ['nullable', 'string', 'in:affiliate,member'],
            'subscription_type' => ['nullable', 'string', 'in:mensuel,annuel'],
            'accept_terms' => ['required', 'accepted'],
            'accept_newsletter' => ['sometimes', 'boolean'],
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
            'nom.required' => 'Le nom est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions générales.',
            'parrain_code.exists' => 'Le code de parrainage est invalide.',
        ];
    }
}

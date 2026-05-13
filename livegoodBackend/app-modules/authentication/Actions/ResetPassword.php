<?php
declare(strict_types=1);

namespace Modules\Authentication\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Models\User;
use Modules\Authentication\Models\PasswordReset;

/**
 * Action ResetPassword
 *
 * Gère la réinitialisation du mot de passe.
 * Vérifie le token, valide le nouveau mot de passe,
 * et met à jour les informations utilisateur.
 *
 * @package Modules\Authentication\Actions
 */
class ResetPassword
{
    /**
     * Exécute la réinitialisation du mot de passe
     *
     * @param string $email
     * @param string $token
     * @param string $newPassword
     * @return array{success: bool, message: string}
     */
    public function execute(string $email, string $token, string $newPassword): array
    {
        // Rechercher le token
        $resetRecord = PasswordReset::where('email', $email)
            ->where('token', $token)
            ->first();

        // Vérifier l'existence et l'expiration du token
        if (!$resetRecord) {
            return [
                'success' => false,
                'message' => 'Token de réinitialisation invalide.',
            ];
        }

        if ($resetRecord->isExpired()) {
            $resetRecord->delete();
            return [
                'success' => false,
                'message' => 'Le lien de réinitialisation a expiré.',
            ];
        }

        // Rechercher l'utilisateur
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Aucun compte trouvé avec cet email.',
            ];
        }

        // Valider la force du mot de passe
        $validation = $this->validatePasswordStrength($newPassword);
        if (!$validation['valid']) {
            return $validation;
        }

        // Mettre à jour le mot de passe
        return DB::transaction(function () use ($user, $newPassword, $resetRecord) {
            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            // Supprimer tous les tokens de réinitialisation pour cet email
            PasswordReset::where('email', $user->email)->delete();

            // Révoquer tous les tokens d'accès (forcer reconnexion)
            $user->tokens()->delete();

            // Journaliser le changement
            \Log::channel('auth')->info('Mot de passe réinitialisé', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
            ]);

            return [
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès. Veuillez vous connecter.',
            ];
        });
    }

    /**
     * Valide la force du mot de passe
     *
     * @param string $password
     * @return array{valid: bool, message?: string}
     */
    private function validatePasswordStrength(string $password): array
    {
        $minLength = config('authentication.password.min_length', 8);

        if (strlen($password) < $minLength) {
            return [
                'valid' => false,
                'message' => "Le mot de passe doit contenir au moins {$minLength} caractères.",
            ];
        }

        if (config('authentication.password.requires_uppercase', true) && !preg_match('/[A-Z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Le mot de passe doit contenir au moins une lettre majuscule.',
            ];
        }

        if (config('authentication.password.requires_numeric', true) && !preg_match('/[0-9]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Le mot de passe doit contenir au moins un chiffre.',
            ];
        }

        if (config('authentication.password.requires_special', true) && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Le mot de passe doit contenir au moins un caractère spécial.',
            ];
        }

        return ['valid' => true];
    }
}
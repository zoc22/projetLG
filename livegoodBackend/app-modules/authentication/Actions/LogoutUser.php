<?php
declare(strict_types=1);

namespace Modules\Authentication\Actions;

use Modules\Authentication\Models\User;
use Modules\Authentication\Models\LoginHistory;

/**
 * Action LogoutUser
 *
 * Gère la déconnexion d'un utilisateur.
 * Révoque le token actuel et met à jour l'historique.
 *
 * @package Modules\Authentication\Actions
 */
class LogoutUser
{
    /**
     * Exécute la déconnexion
     *
     * @param User $user
     * @param string|null $tokenId
     * @return array{success: bool, message: string}
     */
    public function execute(User $user, ?string $tokenId = null): array
    {
        try {
            // Révoquer le token actuel
            if ($tokenId) {
                $user->tokens()->where('id', $tokenId)->delete();
            } else {
                $user->currentAccessToken()?->delete();
            }

            // Mettre à jour l'historique de connexion
            $this->updateLogoutHistory($user);

            // Journaliser la déconnexion
            \Log::channel('auth')->info('Utilisateur déconnecté', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
            ]);

            return [
                'success' => true,
                'message' => 'Déconnexion réussie.',
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la déconnexion', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la déconnexion.',
            ];
        }
    }

    /**
     * Met à jour l'historique de déconnexion
     *
     * @param User $user
     * @return void
     */
    private function updateLogoutHistory(User $user): void
    {
        $lastLogin = LoginHistory::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->orderBy('login_at', 'desc')
            ->first();

        if ($lastLogin) {
            $lastLogin->update(['logout_at' => now()]);
        }
    }
}
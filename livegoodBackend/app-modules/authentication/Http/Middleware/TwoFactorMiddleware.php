<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware TwoFactorMiddleware
 *
 * Vérifie que l'utilisateur a validé la 2FA.
 * Bloque l'accès si la 2FA est activée mais non validée.
 *
 * @package Modules\Authentication\Http\Middleware
 */
class TwoFactorMiddleware
{
    /**
     * Traite la requête entrante
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si la 2FA n'est pas activée, on laisse passer
        if (!$user || !$this->isTwoFactorEnabled($user)) {
            return $next($request);
        }

        // Vérifier si la 2FA a été validée pendant cette session
        if (!$this->isTwoFactorVerified($request, $user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification à deux facteurs requise.',
                    'requires_2fa' => true,
                ], 401);
            }

            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }

    /**
     * Vérifie si la 2FA est activée pour l'utilisateur
     *
     * @param User $user
     * @return bool
     */
    private function isTwoFactorEnabled($user): bool
    {
        return !is_null($user->two_factor_secret) 
            && !is_null($user->two_factor_confirmed_at);
    }

    /**
     * Vérifie si la 2FA a été validée
     *
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function isTwoFactorVerified(Request $request, $user): bool
    {
        return $request->session()->get('2fa.verified') === $user->id;
    }
}
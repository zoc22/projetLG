<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Enums\UserRoleEnum;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 *
 * Vérifie que l'utilisateur authentifié a le rôle requis.
 * Accepte plusieurs rôles séparés par des pipes (|).
 *
 * @package Modules\Authentication\Http\Middleware
 */
class CheckRole
{
    /**
     * Traite la requête entrante
     *
     * @param Request $request
     * @param Closure $next
     * @param string $roles
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return $this->unauthorizedResponse($request);
        }

        $allowedRoles = explode('|', $roles);
        $userRole = $user->type_utilisateur->value;

        if (!in_array($userRole, $allowedRoles)) {
            return $this->forbiddenResponse($request);
        }

        return $next($request);
    }

    /**
     * Réponse pour non authentifié
     *
     * @param Request $request
     * @return Response
     */
    private function unauthorizedResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise.',
            ], 401);
        }

        return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
    }

    /**
     * Réponse pour permission refusée
     *
     * @param Request $request
     * @return Response
     */
    private function forbiddenResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas les permissions nécessaires.',
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
    }
}
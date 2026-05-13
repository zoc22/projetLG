<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware Authenticate
 *
 * Vérifie que l'utilisateur est authentifié.
 * Redirige vers la page de connexion si non authentifié.
 *
 * @package Modules\Authentication\Http\Middleware
 */
class Authenticate
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
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié.',
                ], 401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
<?php
declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\ModuleManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ModuleMiddleware
 *
 * Vérifie qu'un module est activé avant d'accéder à ses routes.
 * Redirige vers une page 404 si le module est désactivé.
 *
 * @package Modules\Core\Http\Middleware
 */

class ModuleMiddleware
{
    /**
     * Instance du gestionnaire de modules
     *
     * @var ModuleManager
     */
    protected ModuleManager $moduleManager;

    /**
     * Constructeur - Injection de dépendance
     *
     * @param ModuleManager $moduleManager
     */
    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Traite la requête entrante
     *
     * @param Request $request Requête HTTP
     * @param Closure $next Prochain middleware
     * @param string $moduleName Nom du module à vérifier
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $moduleName): Response
    {
        // Vérifier si le module est activé
        if (!$this->moduleManager->isEnabled($moduleName)) {
            if (config('app.debug')) {
                abort(404, "Module '{$moduleName}' is not enabled or installed.");
            }

            abort(404);
        }

        // Ajouter le module à la requête pour usage ultérieur
        $request->attributes->set('current_module', $moduleName);

        return $next($request);
    }
}

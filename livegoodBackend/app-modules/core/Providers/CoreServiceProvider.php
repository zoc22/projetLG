<?php
declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Modules\Core\Services\ModuleManager;

/**
 * CoreServiceProvider
 *
 * Fournisseur de services principal du module Core.
 * Enregistre les services, charge les configurations et les routes.
 *
 * @package Modules\Core\Providers
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Nom du module
     *
     * @var string
     */
    protected string $moduleName = 'Core';

    /**
     * Nom du module en minuscules
     *
     * @var string
     */
    protected string $moduleNameLower = 'core';

    /**
     * Chemin de base du module
     *
     * @var string
     */
    protected string $modulePath;

    /**
     * Constructeur
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->modulePath = base_path("app-modules/{$this->moduleName}");
    }

    /**
     * Boot du module - Exécuté après l'enregistrement
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerRoutes();
        $this->registerMiddleware();
    }

    /**
     * Register du module - Exécuté pendant l'enregistrement
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager();
        });

        $this->app->alias(ModuleManager::class, 'module.manager');

        // Charger les helpers
        $helpersPath = $this->modulePath . '/Helpers/helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Enregistre les configurations du module
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $configPath = $this->modulePath . '/Config';

        if (file_exists($configPath . '/module.php')) {
            $this->mergeConfigFrom($configPath . '/module.php', $this->moduleNameLower);
        }

        if (file_exists($configPath . '/permissions.php')) {
            $this->mergeConfigFrom($configPath . '/permissions.php', "{$this->moduleNameLower}.permissions");
        }
    }

    /**
     * Enregistre les routes du module
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $routesPath = $this->modulePath . '/routes';

        if (!is_dir($routesPath)) {
            return;
        }

        // Routes API
        if (file_exists($routesPath . '/api.php')) {
            Route::prefix('api')
                ->middleware(['api'])
                ->group($routesPath . '/api.php');
        }

        // Routes web
        if (file_exists($routesPath . '/web.php')) {
            Route::middleware(['web'])
                ->group($routesPath . '/web.php');
        }
    }

    /**
     * Enregistre les middlewares personnalisés
     *
     * @return void
     */
    protected function registerMiddleware(): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app->make('router');

        $router->aliasMiddleware('module', \Modules\Core\Http\Middleware\ModuleMiddleware::class);
        $router->aliasMiddleware('permission', \Modules\Core\Http\Middleware\PermissionMiddleware::class);
    }
}

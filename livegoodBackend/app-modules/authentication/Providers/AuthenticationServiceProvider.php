<?php
declare(strict_types=1);

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * AuthenticationServiceProvider
 *
 * Fournisseur de services principal du module Authentication.
 * Enregistre les services, charge les configurations et les routes.
 *
 * @package Modules\Authentication\Providers
 */
class AuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Nom du module
     *
     * @var string
     */
    protected string $moduleName = 'Authentication';

    /**
     * Nom du module en minuscules
     *
     * @var string
     */
    protected string $moduleNameLower = 'authentication';

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
        \Log::info('AuthenticationServiceProvider booted');
        $this->registerConfig();
        $this->registerRoutes();
    }

    /**
     * Register du module - Exécuté pendant l'enregistrement
     *
     * @return void
     */
    public function register(): void
    {
        // Enregistrement des services spécifiques au module Authentication
    }

    /**
     * Enregistre les configurations du module
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $configPath = $this->modulePath . '/Config';

        if (file_exists($configPath . '/config.php')) {
            $this->mergeConfigFrom($configPath . '/config.php', $this->moduleNameLower);
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
}

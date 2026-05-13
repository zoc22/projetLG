<?php
declare(strict_types=1);

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Services\AuthService;
use Modules\Authentication\Services\TwoFactorService;
use Modules\Authentication\Services\SocialAuthService;
use Modules\Authentication\Actions\AuthenticateUser;
use Modules\Authentication\Actions\LoginUser;
use Modules\Authentication\Actions\LogoutUser;
use Modules\Authentication\Actions\RegisterUser;
use Modules\Authentication\Actions\ResetPassword;
use Modules\Authentication\Http\Middleware\Authenticate;
use Modules\Authentication\Http\Middleware\CheckRole;
use Modules\Authentication\Http\Middleware\TwoFactorMiddleware;

/**
 * Service Provider AuthServiceProvider
 *
 * Enregistre les services et actions du module d'authentification.
 * Configure les middlewares et les guards.
 *
 * @package Modules\Authentication\Providers
 */
class AuthServiceProvider extends ServiceProvider
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
     * Boot du module
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerMigrations();
        $this->registerMiddleware();
        $this->registerRoutes();
        $this->registerEvents();
    }

    /**
     * Register du module
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerActions();
        $this->registerServices();
        $this->registerGuard();
    }

    /**
     * Enregistre les configurations
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->moduleName, 'Config');

        if (file_exists($configPath . '/config.php')) {
            $this->mergeConfigFrom(
                $configPath . '/config.php',
                $this->moduleNameLower
            );
        }
    }

    /**
     * Enregistre les vues
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $viewsPath = module_path($this->moduleName, 'Views');
        
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $this->moduleNameLower);
        }
    }

    /**
     * Enregistre les migrations
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $migrationsPath = module_path($this->moduleName, 'Database/Migrations');
        
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    /**
     * Enregistre les middlewares
     *
     * @return void
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];

        // Middlewares d'authentification
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('role', CheckRole::class);
        $router->aliasMiddleware('2fa', TwoFactorMiddleware::class);
    }

    /**
     * Enregistre les routes
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $routesPath = module_path($this->moduleName, 'Routes');

        if (!is_dir($routesPath)) {
            return;
        }

        // Routes API
        if (file_exists($routesPath . '/api.php')) {
            $this->app->router->prefix('api')
                ->middleware(['api'])
                ->group($routesPath . '/api.php');
        }

        // Routes web
        if (file_exists($routesPath . '/web.php')) {
            $this->app->router->middleware(['web'])
                ->group($routesPath . '/web.php');
        }
    }

    /**
     * Enregistre les événements
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        $events = $this->app['events'];

        // UserRegistered event
        $events->listen(
            \Modules\Authentication\Events\UserRegistered::class,
            \Modules\Authentication\Listeners\SendWelcomeEmail::class
        );

        // Log authentication activities
        $events->listen(
            \Modules\Authentication\Events\UserLoggedIn::class,
            \Modules\Authentication\Listeners\LogAuthenticationActivity::class
        );

        $events->listen(
            \Modules\Authentication\Events\UserLoggedOut::class,
            \Modules\Authentication\Listeners\LogAuthenticationActivity::class
        );

        $events->listen(
            \Modules\Authentication\Events\UserRegistered::class,
            \Modules\Authentication\Listeners\LogAuthenticationActivity::class
        );
    }

    /**
     * Enregistre les actions du module
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(AuthenticateUser::class);
        $this->app->singleton(LoginUser::class);
        $this->app->singleton(LogoutUser::class);
        $this->app->singleton(RegisterUser::class);
        $this->app->singleton(ResetPassword::class);
    }

    /**
     * Enregistre les services du module
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton(AuthService::class);
        $this->app->singleton(TwoFactorService::class);
        $this->app->singleton(SocialAuthService::class);
    }

    /**
     * Enregistre le guard personnalisé
     *
     * @return void
     */
    protected function registerGuard(): void
    {
        Auth::extend('jwt', function ($app, $name, array $config) {
            $guard = new \Illuminate\Auth\SessionGuard(
                $name,
                $app['auth']->createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );

            $guard->setCookieJar($app['cookie']);
            $guard->setDispatcher($app['events']);
            $guard->setRequest($app['refresh']);

            return $guard;
        });
    }
}
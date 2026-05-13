<?php
declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * ModuleServiceProvider
 *
 * Détecte et enregistre automatiquement tous les modules.
 *
 * @package Modules\Core\Providers
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot - Détecte et charge tous les modules
     *
     * @return void
     */
    public function boot(): void
    {
        $this->discoverAndLoadModules();
    }

    /**
     * Register - Enregistre les services principaux
     *
     * @return void
     */
    public function register(): void
    {
        // Rien à enregistrer ici, les modules sont chargés dans boot()
    }

    /**
     * Découvre et charge tous les modules
     *
     * @return void
     */
    protected function discoverAndLoadModules(): void
    {
        $modulesPath = base_path('app-modules');

        if (!is_dir($modulesPath)) {
            if (config('app.debug')) {
                Log::warning('Modules directory not found at: ' . $modulesPath);
            }
            return;
        }

        $modules = array_filter(glob($modulesPath . '/*'), 'is_dir');

        foreach ($modules as $module) {
            $moduleName = basename($module);

            if ($moduleName === 'Core') {
                continue;
            }

            $this->loadModuleIfEnabled($moduleName);
        }
    }

    /**
     * Charge un module s'il est activé
     *
     * @param string $moduleName
     * @return void
     */
    protected function loadModuleIfEnabled(string $moduleName): void
    {
        try {
            if (!$this->isModuleEnabled($moduleName)) {
                if (config('app.debug')) {
                    Log::info("Module '{$moduleName}' is disabled, skipping load.");
                }
                return;
            }

            $providerClass = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);

                if (config('core.debug')) {
                    Log::info("Module '{$moduleName}' loaded successfully.");
                }
            } else {
                Log::warning("Service provider not found for module '{$moduleName}': {$providerClass}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to load module '{$moduleName}': " . $e->getMessage());
        }
    }

    /**
     * Vérifie si un module est activé
     *
     * @param string $moduleName
     * @return bool
     */
    protected function isModuleEnabled(string $moduleName): bool
    {
        $composerPath = base_path("app-modules/{$moduleName}/composer.json");

        if (File::exists($composerPath)) {
            $config = json_decode(File::get($composerPath), true);

            if (isset($config['extra']['active'])) {
                return (bool) $config['extra']['active'];
            }
        }

        return true;
    }
}

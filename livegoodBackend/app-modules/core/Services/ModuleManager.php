<?php
declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * ModuleManager
 *
 * Gère la découverte, l'activation, la désactivation et l'état des modules.
 * Utilise le cache pour optimiser les performances.
 *
 * @package Modules\Core\Services
 */
class ModuleManager
{
    /**
     * Cache key pour la liste des modules
     */
    private const CACHE_KEY_MODULES = 'core.modules.list';

    /**
     * Durée du cache (secondes)
     */
    private const CACHE_TTL = 3600;

    /**
     * Liste des modules
     *
     * @var Collection|null
     */
    private ?Collection $modules = null;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->loadModules();
    }

    /**
     * Charge les modules depuis le cache ou le disque
     *
     * @return void
     */
    protected function loadModules(): void
    {
        $this->modules = Cache::remember(self::CACHE_KEY_MODULES, self::CACHE_TTL, function () {
            return $this->scanModules();
        });
    }

    /**
     * Scanne le disque pour découvrir les modules
     *
     * @return Collection
     */
    protected function scanModules(): Collection
    {
        $modules = collect();
        $modulesPath = base_path('app-modules');

        if (!is_dir($modulesPath)) {
            return $modules;
        }

        foreach (glob($modulesPath . '/*', GLOB_ONLYDIR) as $modulePath) {
            $moduleName = basename($modulePath);
            $moduleData = $this->getModuleMetadata($modulePath, $moduleName);

            if ($moduleData) {
                $modules->put($moduleName, $moduleData);
            }
        }

        return $modules;
    }

    /**
     * Récupère les métadonnées d'un module
     *
     * @param string $modulePath
     * @param string $moduleName
     * @return array|null
     */
    protected function getModuleMetadata(string $modulePath, string $moduleName): ?array
    {
        $composerFile = $modulePath . '/composer.json';

        if (!File::exists($composerFile)) {
            return null;
        }

        try {
            $composerConfig = json_decode(File::get($composerFile), true);

            return [
                'name' => $moduleName,
                'version' => $composerConfig['version'] ?? '1.0.0',
                'description' => $composerConfig['description'] ?? '',
                'active' => $composerConfig['extra']['active'] ?? true,
                'dependencies' => $composerConfig['extra']['dependencies'] ?? [],
                'order' => $composerConfig['extra']['order'] ?? 999,
                'provider' => $composerConfig['extra']['provider'] ?? "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider",
                'path' => $modulePath,
                'migrations_path' => $modulePath . '/Database/Migrations',
                'routes_path' => $modulePath . '/Routes',
                'config_path' => $modulePath . '/Config',
                'installed_at' => $composerConfig['extra']['installed_at'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error("Failed to read module metadata for '{$moduleName}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un module est activé
     *
     * @param string $moduleName
     * @return bool
     */
    public function isEnabled(string $moduleName): bool
    {
        $module = $this->modules->get($moduleName);

        if (!$module) {
            return false;
        }

        if (!$this->checkDependencies($moduleName)) {
            return false;
        }

        return (bool) $module['active'];
    }

    /**
     * Vérifie les dépendances
     *
     * @param string $moduleName
     * @return bool
     */
    public function checkDependencies(string $moduleName): bool
    {
        $module = $this->modules->get($moduleName);

        if (!$module || empty($module['dependencies'])) {
            return true;
        }

        foreach ($module['dependencies'] as $dependency) {
            if (!$this->isEnabled($dependency)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Active un module
     *
     * @param string $moduleName
     * @return bool
     */
    public function enableModule(string $moduleName): bool
    {
        $module = $this->modules->get($moduleName);

        if (!$module) {
            return false;
        }

        if (!$this->checkDependencies($moduleName)) {
            Log::warning("Cannot enable module '{$moduleName}': missing dependencies");
            return false;
        }

        $composerFile = $module['path'] . '/composer.json';
        $config = json_decode(File::get($composerFile), true);
        $config['extra']['active'] = true;
        File::put($composerFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $module['active'] = true;
        $this->modules->put($moduleName, $module);
        $this->clearCache();

        Log::info("Module '{$moduleName}' enabled successfully");

        return true;
    }

    /**
     * Désactive un module
     *
     * @param string $moduleName
     * @return bool
     */
    public function disableModule(string $moduleName): bool
    {
        $module = $this->modules->get($moduleName);

        if (!$module) {
            return false;
        }

        $dependentModules = $this->findDependentModules($moduleName);

        if (!empty($dependentModules)) {
            Log::warning("Cannot disable module '{$moduleName}', required by: " . implode(', ', $dependentModules));
            return false;
        }

        $composerFile = $module['path'] . '/composer.json';
        $config = json_decode(File::get($composerFile), true);
        $config['extra']['active'] = false;
        File::put($composerFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $module['active'] = false;
        $this->modules->put($moduleName, $module);
        $this->clearCache();

        Log::info("Module '{$moduleName}' disabled successfully");

        return true;
    }

    /**
     * Trouve les modules dépendants
     *
     * @param string $moduleName
     * @return array
     */
    protected function findDependentModules(string $moduleName): array
    {
        $dependents = [];

        foreach ($this->modules as $name => $module) {
            if ($module['active'] && in_array($moduleName, $module['dependencies'])) {
                $dependents[] = $name;
            }
        }

        return $dependents;
    }

    /**
     * Récupère tous les modules
     *
     * @return Collection
     */
    public function getAllModules(): Collection
    {
        return $this->modules;
    }

    /**
     * Récupère les modules activés
     *
     * @return Collection
     */
    public function getEnabledModules(): Collection
    {
        return $this->modules->filter(function ($module) {
            return $module['active'] && $this->checkDependencies($module['name']);
        })->sortBy('order');
    }

    /**
     * Récupère les modules désactivés
     *
     * @return Collection
     */
    public function getDisabledModules(): Collection
    {
        return $this->modules->filter(function ($module) {
            return !$module['active'] || !$this->checkDependencies($module['name']);
        });
    }

    /**
     * Récupère un module spécifique
     *
     * @param string $moduleName
     * @return array|null
     */
    public function getModule(string $moduleName): ?array
    {
        return $this->modules->get($moduleName);
    }

    /**
     * Vérifie si un module existe
     *
     * @param string $moduleName
     * @return bool
     */
    public function hasModule(string $moduleName): bool
    {
        return $this->modules->has($moduleName);
    }

    /**
     * Vide le cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_MODULES);
        $this->loadModules();
    }

    /**
     * Rafraîchit les modules
     *
     * @return void
     */
    public function refreshModules(): void
    {
        $this->modules = $this->scanModules();
        $this->clearCache();
    }
}

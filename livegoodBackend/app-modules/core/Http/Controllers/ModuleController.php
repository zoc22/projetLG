<?php
declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Services\ModuleManager;

/**
 * ModuleController
 *
 * Contrôleur pour tester et gérer les modules de l'application.
 * Permet de lister, activer, désactiver et vérifier l'état des modules.
 *
 * @package Modules\Core\Http\Controllers
 */
class ModuleController
{
    /**
     * Gestionnaire de modules
     *
     * @var ModuleManager
     */
    protected ModuleManager $moduleManager;

    /**
     * Constructeur
     *
     * @param ModuleManager $moduleManager
     */
    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Liste tous les modules disponibles
     *
     * @return JsonResponse
     */
    public function listModules(): JsonResponse
    {
        try {
            $modules = collect();
            $modulesPath = base_path('app-modules');

            if (!is_dir($modulesPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Modules directory not found',
                    'code' => 404,
                    'data' => [],
                ], 404);
            }

            foreach (glob($modulesPath . '/*', GLOB_ONLYDIR) as $modulePath) {
                $moduleName = basename($modulePath);

                $composerFile = $modulePath . '/composer.json';
                if (file_exists($composerFile)) {
                    $composerConfig = json_decode(file_get_contents($composerFile), true);

                    $modules->push([
                        'name' => $moduleName,
                        'version' => $composerConfig['version'] ?? '1.0.0',
                        'description' => $composerConfig['description'] ?? '',
                        'active' => $composerConfig['extra']['active'] ?? true,
                        'dependencies' => $composerConfig['extra']['dependencies'] ?? [],
                        'enabled' => $this->moduleManager->isEnabled($moduleName),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Modules listed successfully',
                'code' => 200,
                'data' => $modules,
                'count' => $modules->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error listing modules: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère les détails d'un module
     *
     * @param string $moduleName Nom du module
     * @return JsonResponse
     */
    public function getModuleDetails(string $moduleName): JsonResponse
    {
        try {
            $modulePath = base_path("app-modules/{$moduleName}");

            if (!is_dir($modulePath)) {
                return response()->json([
                    'success' => false,
                    'message' => "Module '{$moduleName}' not found",
                    'code' => 404,
                    'data' => null,
                ], 404);
            }

            $composerFile = $modulePath . '/composer.json';
            if (!file_exists($composerFile)) {
                return response()->json([
                    'success' => false,
                    'message' => "Module '{$moduleName}' configuration not found",
                    'code' => 404,
                    'data' => null,
                ], 404);
            }

            $composerConfig = json_decode(file_get_contents($composerFile), true);
            $isEnabled = $this->moduleManager->isEnabled($moduleName);
            $hasDependencies = !empty($composerConfig['extra']['dependencies'] ?? []);
            $dependenciesMet = $this->moduleManager->checkDependencies($moduleName);

            $moduleData = [
                'name' => $moduleName,
                'version' => $composerConfig['version'] ?? '1.0.0',
                'description' => $composerConfig['description'] ?? '',
                'author' => $composerConfig['author'] ?? '',
                'license' => $composerConfig['license'] ?? '',
                'active' => $composerConfig['extra']['active'] ?? true,
                'enabled' => $isEnabled,
                'dependencies' => $composerConfig['extra']['dependencies'] ?? [],
                'dependencies_met' => $dependenciesMet,
                'order' => $composerConfig['extra']['order'] ?? 999,
                'provider' => $composerConfig['extra']['provider'] ?? "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider",
                'path' => $modulePath,
                'has_routes' => is_dir($modulePath . '/routes'),
                'has_migrations' => is_dir($modulePath . '/database/migrations'),
                'has_config' => is_dir($modulePath . '/config'),
            ];

            return response()->json([
                'success' => true,
                'message' => "Module '{$moduleName}' details retrieved successfully",
                'code' => 200,
                'data' => $moduleData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving module details: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Vérifie si un module est activé
     *
     * @param string $moduleName Nom du module
     * @return JsonResponse
     */
    public function checkModuleStatus(string $moduleName): JsonResponse
    {
        try {
            $modulePath = base_path("app-modules/{$moduleName}");

            if (!is_dir($modulePath)) {
                return response()->json([
                    'success' => false,
                    'message' => "Module '{$moduleName}' not found",
                    'code' => 404,
                    'data' => null,
                ], 404);
            }

            $isEnabled = $this->moduleManager->isEnabled($moduleName);
            $hasDependencies = false;
            $dependenciesMet = true;

            $composerFile = $modulePath . '/composer.json';
            if (file_exists($composerFile)) {
                $composerConfig = json_decode(file_get_contents($composerFile), true);
                $hasDependencies = !empty($composerConfig['extra']['dependencies'] ?? []);
                $dependenciesMet = $this->moduleManager->checkDependencies($moduleName);
            }

            return response()->json([
                'success' => true,
                'message' => "Module '{$moduleName}' status retrieved successfully",
                'code' => 200,
                'data' => [
                    'module_name' => $moduleName,
                    'is_enabled' => $isEnabled,
                    'has_dependencies' => $hasDependencies,
                    'dependencies_met' => $dependenciesMet,
                    'path' => $modulePath,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking module status: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Test de la fonction helper module_path
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testHelperModulePath(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'module' => 'required|string',
                'path' => 'nullable|string',
            ]);

            $module = $request->input('module');
            $path = $request->input('path', '');

            $result = module_path($module, $path);

            return response()->json([
                'success' => true,
                'message' => 'module_path() helper executed successfully',
                'code' => 200,
                'data' => [
                    'module' => $module,
                    'relative_path' => $path,
                    'full_path' => $result,
                    'exists' => is_dir($result),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing module_path helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Test de la fonction helper module_config
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testHelperModuleConfig(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'module' => 'required|string',
                'key' => 'required|string',
            ]);

            $module = $request->input('module');
            $key = $request->input('key');

            $result = module_config($module, $key, null);

            return response()->json([
                'success' => true,
                'message' => 'module_config() helper executed successfully',
                'code' => 200,
                'data' => [
                    'module' => $module,
                    'key' => $key,
                    'value' => $result,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing module_config helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Test de la fonction helper module_enabled
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testHelperModuleEnabled(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'module' => 'required|string',
            ]);

            $module = $request->input('module');
            $isEnabled = module_enabled($module);

            return response()->json([
                'success' => true,
                'message' => 'module_enabled() helper executed successfully',
                'code' => 200,
                'data' => [
                    'module' => $module,
                    'is_enabled' => $isEnabled,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing module_enabled helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }
}

<?php
declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * HealthController
 *
 * Contrôleur pour tester la santé du système et les helpers généraux.
 * Permet de vérifier les fonctions utilitaires du module core.
 *
 * @package Modules\Core\Http\Controllers
 */
class HealthController
{
    /**
     * Vérifie la santé de l'application
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Application health is OK',
            'code' => 200,
            'data' => [
                'status' => 'healthy',
                'timestamp' => now()->toIso8601String(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug'),
            ],
        ]);
    }

    /**
     * Teste la fonction helper generate_uuid
     *
     * @return JsonResponse
     */
    public function testGenerateUuid(): JsonResponse
    {
        try {
            $uuid1 = generate_uuid();
            $uuid2 = generate_uuid();

            return response()->json([
                'success' => true,
                'message' => 'generate_uuid() helper executed successfully',
                'code' => 200,
                'data' => [
                    'uuid_1' => $uuid1,
                    'uuid_2' => $uuid2,
                    'are_unique' => $uuid1 !== $uuid2,
                    'format_valid_1' => Str::isUuid($uuid1),
                    'format_valid_2' => Str::isUuid($uuid2),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing generate_uuid helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Teste la fonction helper format_currency
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testFormatCurrency(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'currency' => 'nullable|string|size:3',
                'locale' => 'nullable|string',
            ]);

            $amount = (float) $request->input('amount');
            $currency = $request->input('currency', 'USD');
            $locale = $request->input('locale', 'en_US');

            try {
                $formatted = format_currency($amount, $currency, $locale);
                $success = true;
                $error = null;
            } catch (\Exception $e) {
                $formatted = null;
                $success = false;
                $error = $e->getMessage();
            }

            return response()->json([
                'success' => $success,
                'message' => $success ? 'format_currency() helper executed successfully' : 'Error formatting currency',
                'code' => $success ? 200 : 500,
                'data' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'locale' => $locale,
                    'formatted' => $formatted,
                    'error' => $error,
                ],
            ], $success ? 200 : 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing format_currency helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Teste la fonction helper cache_remember_forever
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testCacheRememberForever(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'key' => 'required|string',
                'value' => 'required|string',
                'tag' => 'nullable|string',
            ]);

            $key = $request->input('key');
            $value = $request->input('value');
            $tag = $request->input('tag');

            $result = cache_remember_forever($key, function () use ($value) {
                return $value;
            }, $tag);

            // Vérifier que la valeur est en cache
            $cached = cache()->has($key);

            return response()->json([
                'success' => true,
                'message' => 'cache_remember_forever() helper executed successfully',
                'code' => 200,
                'data' => [
                    'key' => $key,
                    'value_set' => $value,
                    'value_retrieved' => $result,
                    'is_cached' => $cached,
                    'values_match' => $result === $value,
                    'tag' => $tag,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing cache_remember_forever helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Teste la fonction helper module_asset
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testModuleAsset(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'module' => 'required|string',
                'path' => 'required|string',
            ]);

            $module = $request->input('module');
            $path = $request->input('path');

            $assetUrl = module_asset($module, $path);

            return response()->json([
                'success' => true,
                'message' => 'module_asset() helper executed successfully',
                'code' => 200,
                'data' => [
                    'module' => $module,
                    'path' => $path,
                    'asset_url' => $assetUrl,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing module_asset helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère les informations de configuration du module
     *
     * @return JsonResponse
     */
    public function getCoreConfig(): JsonResponse
    {
        try {
            $coreConfig = config('core');

            return response()->json([
                'success' => true,
                'message' => 'Core configuration retrieved successfully',
                'code' => 200,
                'data' => [
                    'config' => $coreConfig,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving core configuration: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère les permissions disponibles
     *
     * @return JsonResponse
     */
    public function getAvailablePermissions(): JsonResponse
    {
        try {
            $permissions = config('core.permissions.permissions', []);
            $roles = config('core.permissions.roles', []);

            return response()->json([
                'success' => true,
                'message' => 'Available permissions retrieved successfully',
                'code' => 200,
                'data' => [
                    'permissions' => $permissions,
                    'permissions_count' => count($permissions),
                    'roles' => array_keys($roles),
                    'roles_count' => count($roles),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available permissions: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère les informations des rôles
     *
     * @return JsonResponse
     */
    public function getRolesInfo(): JsonResponse
    {
        try {
            $roles = config('core.permissions.roles', []);

            $rolesInfo = [];
            foreach ($roles as $roleName => $roleData) {
                $rolesInfo[$roleName] = [
                    'name' => $roleData['name'] ?? $roleName,
                    'description' => $roleData['description'] ?? '',
                    'permissions' => $roleData['permissions'] ?? [],
                    'permissions_count' => count($roleData['permissions'] ?? []),
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Roles information retrieved successfully',
                'code' => 200,
                'data' => [
                    'roles' => $rolesInfo,
                    'total_roles' => count($rolesInfo),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving roles information: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère le détail d'un rôle spécifique
     *
     * @param string $roleName Nom du rôle
     * @return JsonResponse
     */
    public function getRoleDetails(string $roleName): JsonResponse
    {
        try {
            $role = config("core.permissions.roles.{$roleName}");

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => "Role '{$roleName}' not found",
                    'code' => 404,
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Role '{$roleName}' details retrieved successfully",
                'code' => 200,
                'data' => [
                    'name' => $roleName,
                    'display_name' => $role['name'] ?? $roleName,
                    'description' => $role['description'] ?? '',
                    'permissions' => $role['permissions'] ?? [],
                    'permissions_count' => count($role['permissions'] ?? []),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving role details: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }
}

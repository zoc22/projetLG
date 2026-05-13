<?php
declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * PermissionController
 *
 * Contrôleur pour tester les opérations de permissions et rôles.
 * Permet de vérifier les permissions, rôles et accès utilisateur.
 *
 * @package Modules\Core\Http\Controllers
 */
class PermissionController
{
    /**
     * Vérifie les permissions de l'utilisateur authentifié
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPermission(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $request->validate([
                'permission' => 'required|string',
            ]);

            $permission = $request->input('permission');
            $hasPermission = false;

            if (method_exists($user, 'hasPermission')) {
                $hasPermission = $user->hasPermission($permission);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission check executed successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'permission' => $permission,
                    'has_permission' => $hasPermission,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking permission: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur a TOUS les permissions spécifiées
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAllPermissions(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'string',
            ]);

            $permissions = $request->input('permissions', []);
            $hasAllPermissions = true;
            $results = [];

            if (method_exists($user, 'hasAllPermissions')) {
                $hasAllPermissions = $user->hasAllPermissions($permissions);

                // Détail pour chaque permission
                foreach ($permissions as $permission) {
                    if (method_exists($user, 'hasPermission')) {
                        $results[$permission] = $user->hasPermission($permission);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'All permissions check executed successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'permissions' => $permissions,
                    'has_all_permissions' => $hasAllPermissions,
                    'details' => $results,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking all permissions: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur a AU MOINS UNE des permissions spécifiées
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAnyPermission(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'string',
            ]);

            $permissions = $request->input('permissions', []);
            $hasAnyPermission = false;
            $results = [];

            if (method_exists($user, 'hasAnyPermission')) {
                $hasAnyPermission = $user->hasAnyPermission($permissions);

                // Détail pour chaque permission
                foreach ($permissions as $permission) {
                    if (method_exists($user, 'hasPermission')) {
                        $results[$permission] = $user->hasPermission($permission);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Any permission check executed successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'permissions' => $permissions,
                    'has_any_permission' => $hasAnyPermission,
                    'details' => $results,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking any permission: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Vérifie le rôle de l'utilisateur
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkRole(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $request->validate([
                'role' => 'required|string',
            ]);

            $role = $request->input('role');
            $hasRole = false;

            if (method_exists($user, 'hasRole')) {
                $hasRole = $user->hasRole($role);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role check executed successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'user_role' => $user->role ?? null,
                    'checked_role' => $role,
                    'has_role' => $hasRole,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking role: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur est super admin
     *
     * @return JsonResponse
     */
    public function checkSuperAdmin(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $isSuperAdmin = false;

            if (method_exists($user, 'isSuperAdmin')) {
                $isSuperAdmin = $user->isSuperAdmin();
            }

            return response()->json([
                'success' => true,
                'message' => 'Super admin check executed successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'is_super_admin' => $isSuperAdmin,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking super admin status: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Récupère toutes les permissions de l'utilisateur
     *
     * @return JsonResponse
     */
    public function getUserPermissions(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'code' => 401,
                    'data' => null,
                ], 401);
            }

            $permissions = [];

            if (method_exists($user, 'getPermissions')) {
                $permissions = $user->getPermissions();
            }

            return response()->json([
                'success' => true,
                'message' => 'User permissions retrieved successfully',
                'code' => 200,
                'data' => [
                    'user_id' => $user->id ?? null,
                    'user_email' => $user->email ?? null,
                    'permissions' => $permissions,
                    'total_permissions' => count($permissions),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user permissions: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Test de la fonction helper has_permission
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testHelperHasPermission(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'permission' => 'required|string',
            ]);

            $permission = $request->input('permission');
            $result = has_permission($permission);

            return response()->json([
                'success' => true,
                'message' => 'has_permission() helper executed successfully',
                'code' => 200,
                'data' => [
                    'permission' => $permission,
                    'result' => $result,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing has_permission helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }

    /**
     * Test de la fonction helper is_admin
     *
     * @return JsonResponse
     */
    public function testHelperIsAdmin(): JsonResponse
    {
        try {
            $result = is_admin();

            return response()->json([
                'success' => true,
                'message' => 'is_admin() helper executed successfully',
                'code' => 200,
                'data' => [
                    'is_admin' => $result,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing is_admin helper: ' . $e->getMessage(),
                'code' => 500,
                'data' => null,
            ], 500);
        }
    }
}

<?php
declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware PermissionMiddleware
 *
 * Vérifie que l'utilisateur authentifié possède la permission requise.
 * Utilise le trait HasPermissions sur le modèle User.
 *
 * @package Modules\Core\Http\Middleware
 */
class PermissionMiddleware
{
    /**
     * Liste des permissions super admin
     */
    private const SUPER_ADMIN_PERMISSIONS = ['admin', '*', 'super_admin'];

    /**
     * Traite la requête entrante
     *
     * @param Request $request Requête HTTP
     * @param Closure $next Prochain middleware
     * @param string $permission Permission requise
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Authentication required.');
        }

        $permissions = explode('|', $permission);
        $hasPermission = false;

        foreach ($permissions as $perm) {
            if ($this->checkPermission($user, $perm)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            abort(403, "Insufficient permissions. Required: {$permission}");
        }

        return $next($request);
    }

    /**
     * Vérifie une permission spécifique
     *
     * @param mixed $user
     * @param string $permission
     * @return bool
     */
    private function checkPermission($user, string $permission): bool
    {
        if (in_array($permission, self::SUPER_ADMIN_PERMISSIONS)) {
            return $this->isSuperAdmin($user);
        }

        if (method_exists($user, 'hasPermission')) {
            return $user->hasPermission($permission);
        }

        return $this->basicPermissionCheck($user, $permission);
    }

    /**
     * Vérifie si l'utilisateur est super admin
     *
     * @param mixed $user
     * @return bool
     */
    private function isSuperAdmin($user): bool
    {
        if (method_exists($user, 'isSuperAdmin')) {
            return $user->isSuperAdmin();
        }

        $superAdminEmails = explode(',', config('core.super_admins', ''));
        if (!empty($superAdminEmails) && in_array($user->email, $superAdminEmails)) {
            return true;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin');
        }

        return false;
    }

    /**
     * Vérification basique des permissions
     *
     * @param mixed $user
     * @param string $permission
     * @return bool
     */
    private function basicPermissionCheck($user, string $permission): bool
    {
        $role = $user->role ?? null;

        if (!$role) {
            return false;
        }

        $rolePermissions = config("core.permissions.roles.{$role}.permissions", []);

        if (in_array($permission, $rolePermissions)) {
            return true;
        }

        foreach ($rolePermissions as $rolePerm) {
            if (str_ends_with($rolePerm, '.*')) {
                $prefix = substr($rolePerm, 0, -2);
                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }
}

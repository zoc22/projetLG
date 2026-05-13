<?php
declare(strict_types=1);

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * Trait HasPermissions
 *
 * Ajoute des méthodes de gestion des permissions à un modèle Eloquent.
 * Permet la vérification des droits d'accès, la gestion des rôles,
 * et le cache des permissions pour optimiser les performances.
 *
 * Ce trait est destiné à être utilisé sur les modèles Eloquent et suppose que le modèle
 * possède les propriétés et méthodes suivantes:
 * - Propriété: $role (string) - Le rôle de l'utilisateur
 * - Propriété: $email (string) - L'email de l'utilisateur
 * - Relation: roles() - Relation avec les rôles
 * - Relation: permissions() - Relation avec les permissions
 * - Méthode Eloquent: getAttribute(), getKey(), relationLoaded(), wasChanged()
 *
 * @package Modules\Core\Traits
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property string $role
 * @property string $email
 * @method mixed getAttribute(string $key)
 * @method mixed getKey()
 * @method bool relationLoaded(string $key)
 * @method bool wasChanged(...$args)
 */
trait HasPermissions
{
    /**
     * Préfixe du cache des permissions
     */
    private const PERMISSION_CACHE_PREFIX = 'user_permissions_';

    /**
     * Durée du cache (secondes)
     */
    private const PERMISSION_CACHE_TTL = 86400;

    /**
     * Permissions en cache
     *
     * @var array|null
     */
    protected ?array $cachedPermissions = null;

    /**
     * Vérifie si l'utilisateur a une permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->getPermissions();

        if (in_array($permission, $permissions)) {
            return true;
        }

        foreach ($permissions as $userPerm) {
            if ($this->isWildcardMatch($userPerm, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur a toutes les permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si l'utilisateur a une des permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur a un rôle
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $roleAttribute = $this->role ?? $this->getAttribute('role');

        if ($roleAttribute === $role) {
            return true;
        }

        if (method_exists($this, 'roles') && $this->relationLoaded('roles')) {
            foreach ($this->roles as $userRole) {
                if ($userRole->name === $role || $userRole->slug === $role) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur est super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        $superAdminEmails = config('core.super_admins', '');
        $emails = is_string($superAdminEmails) ? explode(',', $superAdminEmails) : [];

        if (!empty($emails) && in_array($this->email, $emails)) {
            return true;
        }

        if ($this->hasRole('super_admin')) {
            return true;
        }

        return false;
    }

    /**
     * Récupère les permissions
     *
     * @return array
     */
    public function getPermissions(): array
    {
        if ($this->cachedPermissions !== null) {
            return $this->cachedPermissions;
        }

        $cacheKey = $this->getPermissionCacheKey();

        $permissions = Cache::remember($cacheKey, self::PERMISSION_CACHE_TTL, function () {
            return $this->loadPermissionsFromDatabase();
        });

        $this->cachedPermissions = $permissions;

        return $permissions;
    }

    /**
     * Charge les permissions depuis la base de données
     *
     * @return array
     */
    protected function loadPermissionsFromDatabase(): array
    {
        $permissions = [];

        $role = $this->role ?? $this->getAttribute('role');
        if ($role) {
            $rolePermissions = config("core.permissions.roles.{$role}.permissions", []);
            $permissions = array_merge($permissions, $rolePermissions);
        }

        if (method_exists($this, 'permissions')) {
            $directPermissions = $this->permissions()->pluck('name')->toArray();
            $permissions = array_merge($permissions, $directPermissions);
        }

        if (method_exists($this, 'roles')) {
            foreach ($this->roles as $role) {
                if (method_exists($role, 'permissions')) {
                    $rolePerms = $role->permissions()->pluck('name')->toArray();
                    $permissions = array_merge($permissions, $rolePerms);
                }
            }
        }

        return array_unique($permissions);
    }

    /**
     * Vérifie si une permission wildcard correspond
     *
     * @param string $wildcard
     * @param string $permission
     * @return bool
     */
    protected function isWildcardMatch(string $wildcard, string $permission): bool
    {
        if ($wildcard === '*') {
            return true;
        }

        if (str_ends_with($wildcard, '.*')) {
            $prefix = substr($wildcard, 0, -2);
            return str_starts_with($permission, $prefix);
        }

        return false;
    }

    /**
     * Génère la clé de cache
     *
     * @return string
     */
    protected function getPermissionCacheKey(): string
    {
        return self::PERMISSION_CACHE_PREFIX . $this->getKey();
    }

    /**
     * Vide le cache des permissions
     *
     * @return void
     */
    public function clearPermissionCache(): void
    {
        Cache::forget($this->getPermissionCacheKey());
        $this->cachedPermissions = null;
    }

    /**
     * Surcharge de save pour vider le cache
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        $saved = parent::save($options);

        if ($saved && ($this->wasChanged('role') || $this->wasChanged('email'))) {
            $this->clearPermissionCache();
        }

        return $saved;
    }
}

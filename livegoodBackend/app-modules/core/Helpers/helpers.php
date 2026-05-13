<?php
declare(strict_types=1);

/**
 * Fichier des fonctions helpers globales pour le module Core
 *
 * Ces fonctions sont disponibles dans toute l'application.
 * Elles fournissent des utilitaires réutilisables pour la gestion
 * des modules, des permissions, et des fonctionnalités système.
 *
 * @package Modules\Core\Helpers
 */

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\ModuleManager;

if (!function_exists('module_path')) {
    /**
     * Obtient le chemin d'accès à un module
     *
     * @param string $module Nom du module
     * @param string $path Chemin relatif supplémentaire
     * @return string
     */
    function module_path(string $module, string $path = ''): string
    {
        $basePath = base_path("app-modules/{$module}");

        if (!empty($path)) {
            $basePath = rtrim($basePath, '/') . '/' . ltrim($path, '/');
        }

        return $basePath;
    }
}

if (!function_exists('module_asset')) {
    /**
     * Génère l'URL d'un asset d'un module
     *
     * @param string $module Nom du module
     * @param string $path Chemin de l'asset
     * @return string
     */
    function module_asset(string $module, string $path): string
    {
        return asset("modules/{$module}/{$path}");
    }
}

if (!function_exists('module_config')) {
    /**
     * Récupère une configuration de module
     *
     * @param string $module Nom du module
     * @param string $key Clé de configuration
     * @param mixed $default Valeur par défaut
     * @return mixed
     */
    function module_config(string $module, string $key, $default = null)
    {
        return config("{$module}.{$key}", $default);
    }
}

if (!function_exists('module_enabled')) {
    /**
     * Vérifie si un module est activé
     *
     * @param string $module Nom du module
     * @return bool
     */
    function module_enabled(string $module): bool
    {
        static $manager = null;

        if ($manager === null && app()->bound(ModuleManager::class)) {
            $manager = app(ModuleManager::class);
        }

        return $manager ? $manager->isEnabled($module) : false;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Formate un montant en devise
     *
     * @param float $amount Montant à formater
     * @param string $currency Devise
     * @param string $locale Locale
     * @return string
     */
    function format_currency(float $amount, string $currency = 'USD', string $locale = 'en_US'): string
    {
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }
}

if (!function_exists('generate_uuid')) {
    /**
     * Génère un UUID v4
     *
     * @return string
     */
    function generate_uuid(): string
    {
        return (string) Str::uuid();
    }
}

if (!function_exists('has_permission')) {
    /**
     * Vérifie si l'utilisateur actuel a une permission
     *
     * @param string $permission Permission à vérifier
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user Utilisateur spécifique
     * @return bool
     */
    function has_permission(string $permission, ?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasPermission')) {
            return $user->hasPermission($permission);
        }

        return false;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Vérifie si l'utilisateur actuel est administrateur
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user Utilisateur spécifique
     * @return bool
     */
    function is_admin(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        return has_permission('admin', $user);
    }
}

if (!function_exists('cache_remember_forever')) {
    /**
     * Met en cache une valeur indéfiniment
     *
     * @param string $key Clé du cache
     * @param callable $callback Fonction pour générer la valeur
     * @param string|null $tag Tag du cache
     * @return mixed
     */
    function cache_remember_forever(string $key, callable $callback, ?string $tag = null)
    {
        $cache = $tag ? cache()->tags($tag) : cache();

        if ($cache->has($key)) {
            return $cache->get($key);
        }

        $value = $callback();
        $cache->forever($key, $value);

        return $value;
    }
}

if (!function_exists('cache_forget_tag')) {
    /**
     * Supprime tous les caches d'un tag
     *
     * @param string $tag Tag du cache à supprimer
     * @return void
     */
    function cache_forget_tag(string $tag): void
    {
        if (method_exists(cache(), 'tags')) {
            cache()->tags($tag)->flush();
        }
    }
}

if (!function_exists('generate_affiliate_code')) {
    /**
     * Génère un code d'affiliation unique
     *
     * @param int $length Longueur du code
     * @return string
     */
    function generate_affiliate_code(int $length = 8): string
    {
        return strtoupper(Str::random($length));
    }
}

if (!function_exists('calculate_percentage')) {
    /**
     * Calcule un pourcentage
     *
     * @param float $value Valeur
     * @param float $total Total
     * @param int $decimals Nombre de décimales
     * @return float
     */
    function calculate_percentage(float $value, float $total, int $decimals = 2): float
    {
        if ($total == 0) {
            return 0;
        }

        return round(($value / $total) * 100, $decimals);
    }
}

if (!function_exists('get_ip_address')) {
    /**
     * Récupère l'adresse IP du client
     *
     * @return string
     */
    function get_ip_address(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if ($ip = request()->server($header)) {
                $ip = explode(',', $ip)[0];
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return request()->ip() ?? '0.0.0.0';
    }
}

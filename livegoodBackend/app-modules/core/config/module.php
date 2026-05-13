<?php
declare(strict_types=1);

/**
 * Module Core - Configuration principale
 *
 * Ce fichier définit la configuration de base du module Core,
 * incluant les métadonnées du module, les dépendances, et les paramètres système.
 *
 * @package Modules\Core\Config
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Métadonnées du module
    |--------------------------------------------------------------------------
    */
    'name' => 'Core',
    'version' => '1.0.0',
    'description' => 'Module noyau - Gestion des modules, permissions, et fonctionnalités de base',
    'author' => 'NetworkLiveGood',
    'website' => 'https://livegood.com',

    /*
    |--------------------------------------------------------------------------
    | Dépendances du module
    |--------------------------------------------------------------------------
    | Liste des modules requis pour que ce module fonctionne correctement
    */
    'dependencies' => [],

    /*
    |--------------------------------------------------------------------------
    | Configuration système
    |--------------------------------------------------------------------------
    */
    'settings' => [
        'debug' => env('CORE_DEBUG', false),
        'timezone' => env('APP_TIMEZONE', 'UTC'),
        'route_prefix' => 'api',
        'middleware' => ['api', 'auth:sanctum'],
        'pagination' => [
            'per_page' => 15,
            'max_per_page' => 100,
        ],
        'cache' => [
            'ttl' => 3600,
            'prefix' => 'core_',
        ],
        'rate_limit' => [
            'attempts' => 60,
            'decay' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules autodécouverts
    |--------------------------------------------------------------------------
    */
    'discovery' => [
        'paths' => [
            base_path('Modules/*'),
        ],
        'ignore' => [
            'Core',
            '*.git',
            '*.idea',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules activés par défaut
    |--------------------------------------------------------------------------
    */
    'default_enabled_modules' => [
        'Authentication',
        'Affiliation',
        'Genealogy',
    ],

    /*
    |--------------------------------------------------------------------------
    | Super administrateurs (emails)
    |--------------------------------------------------------------------------
    */
    'super_admins' => env('CORE_SUPER_ADMINS', 'admin@livegood.com'),
];

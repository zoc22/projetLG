<?php
declare(strict_types=1);

/**
 * Module Authentication - Configuration
 *
 * Ce fichier définit la configuration du module d'authentification,
 * incluant les paramètres JWT, les durées de session,
 * la validation des mots de passe, et les options de sécurité.
 *
 * @package Modules\Authentication\Config
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration JWT (JSON Web Tokens)
    |--------------------------------------------------------------------------
    */
    'jwt' => [
        // Durée de vie du token d'accès (en minutes)
        'ttl' => env('JWT_TTL', 120), // 2 heures

        // Durée de vie du refresh token (en jours)
        'refresh_ttl' => env('JWT_REFRESH_TTL', 7), // 7 jours

        // Algorithme de signature
        'algo' => env('JWT_ALGO', 'HS256'),

        // Secret key (doit être définie dans .env)
        'secret' => env('JWT_SECRET'),

        // Émetteur du token
        'issuer' => env('APP_URL', 'http://localhost:8000'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de la validation des mots de passe
    |--------------------------------------------------------------------------
    */
    'password' => [
        // Longueur minimale
        'min_length' => 8,

        // Exiger au moins une lettre majuscule
        'requires_uppercase' => true,

        // Exiger au moins un chiffre
        'requires_numeric' => true,

        // Exiger au moins un caractère spécial
        'requires_special' => true,

        // Empêcher les mots de passe communs
        'prevent_common' => true,

        // Expiration du reset token (en minutes)
        'reset_token_expiry' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de l'authentification à deux facteurs (2FA)
    |--------------------------------------------------------------------------
    */
    'two_factor' => [
        // Activer/désactiver la 2FA
        'enabled' => env('2FA_ENABLED', true),

        // Code length (6 digits standard)
        'code_length' => 6,

        // Durée de validité du code (en minutes)
        'code_ttl' => 10,

        // Nombre maximal de tentatives
        'max_attempts' => 5,

        // Délai de blocage après trop de tentatives (en minutes)
        'lockout_time' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration de la sécurité
    |--------------------------------------------------------------------------
    */
    'security' => [
        // Nombre maximal de tentatives de connexion
        'max_login_attempts' => 5,

        // Délai de blocage après trop de tentatives (en minutes)
        'lockout_time' => 15,

        // Forcer la vérification par email
        'force_email_verification' => true,

        // Permettre l'inscription via code de parrainage
        'allow_referral_registration' => true,

        // Sessions simultanées autorisées par utilisateur (0 = illimité)
        'max_concurrent_sessions' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des événements
    |--------------------------------------------------------------------------
    */
    'events' => [
        // Envoyer un email de bienvenue
        'send_welcome_email' => true,

        // Envoyer une notification lors d'une nouvelle connexion
        'send_new_login_notification' => true,

        // Envoyer une notification lors d'un changement de mot de passe
        'send_password_change_notification' => true,

        // Journaliser toutes les activités d'authentification
        'log_all_auth_events' => true,
    ],
];
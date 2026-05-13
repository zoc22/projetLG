<?php
declare(strict_types=1);

/**
 * Module Core - Configuration des permissions
 *
 * Ce fichier définit tous les rôles et permissions disponibles dans l'application.
 *
 * @package Modules\Core\Config
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Rôles système
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'super_admin' => [
            'name' => 'Super Administrateur',
            'description' => 'Accès complet à toutes les fonctionnalités',
            'permissions' => ['*'],
        ],
        'admin' => [
            'name' => 'Administrateur',
            'description' => 'Gestion du système sans accès au noyau',
            'permissions' => [
                'users.*', 'roles.*', 'permissions.*', 'modules.view',
                'config.view', 'statistics.*', 'support.*',
            ],
        ],
        'support' => [
            'name' => 'Support Client',
            'description' => 'Gestion des tickets support',
            'permissions' => ['users.view', 'support.*', 'statistics.view'],
        ],
        'affiliate' => [
            'name' => 'Affilié',
            'description' => 'Membre du réseau LiveGood',
            'permissions' => [
                'dashboard.view', 'genealogy.view', 'genealogy.matrix',
                'commissions.view', 'commissions.details', 'withdrawals.request',
                'websites.manage', 'profile.edit', 'referrals.view', 'statistics.personal',
            ],
        ],
        'member' => [
            'name' => 'Membre',
            'description' => 'Client sans fonctionnalités réseau',
            'permissions' => [
                'profile.view', 'profile.edit', 'shop.view', 'shop.order',
                'orders.history', 'support.create',
            ],
        ],
        'guest' => [
            'name' => 'Invité',
            'description' => 'Utilisateur non authentifié',
            'permissions' => ['auth.login', 'auth.register', 'products.view', 'webinars.view'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Définition détaillée des permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'users.view' => 'Voir la liste des utilisateurs',
        'users.create' => 'Créer des utilisateurs',
        'users.edit' => 'Modifier des utilisateurs',
        'users.delete' => 'Supprimer des utilisateurs',
        'users.impersonate' => 'Prendre l\'identité d\'un utilisateur',
        'users.restore' => 'Restaurer des utilisateurs supprimés',
        'roles.view' => 'Voir les rôles',
        'roles.create' => 'Créer des rôles',
        'roles.edit' => 'Modifier des rôles',
        'roles.delete' => 'Supprimer des rôles',
        'permissions.assign' => 'Attribuer des permissions',
        'dashboard.view' => 'Voir le tableau de bord principal',
        'dashboard.analytics' => 'Voir les analyses avancées',
        'genealogy.view' => 'Voir son arbre généalogique',
        'genealogy.matrix' => 'Voir la matrice de parrainage',
        'genealogy.export' => 'Exporter les données généalogiques',
        'commissions.view' => 'Voir ses commissions',
        'commissions.details' => 'Voir le détail des commissions',
        'commissions.export' => 'Exporter les relevés de commissions',
        'withdrawals.request' => 'Demander un retrait',
        'withdrawals.view' => 'Voir l\'historique des retraits',
        'payments.manage' => 'Gérer les paiements système',
        'websites.manage' => 'Gérer ses sites web personnalisés',
        'websites.statistics' => 'Voir les statistiques des sites',
        'profile.view' => 'Voir son profil',
        'profile.edit' => 'Modifier son profil',
        'profile.password' => 'Changer son mot de passe',
        'referrals.view' => 'Voir ses filleuls',
        'referrals.contact' => 'Contacter ses filleuls',
        'statistics.view' => 'Voir les statistiques globales',
        'statistics.personal' => 'Voir ses statistiques personnelles',
        'statistics.export' => 'Exporter les statistiques',
        'shop.view' => 'Voir la boutique',
        'shop.order' => 'Passer commande',
        'orders.history' => 'Voir l\'historique des commandes',
        'webinars.view' => 'Voir les webinaires',
        'webinars.register' => 'S\'inscrire aux webinaires',
        'trainings.access' => 'Accéder aux formations',
        'support.create' => 'Créer un ticket support',
        'support.view' => 'Voir les tickets support',
        'support.respond' => 'Répondre aux tickets',
        'support.resolve' => 'Résoudre les tickets',
        'config.view' => 'Voir la configuration système',
        'config.edit' => 'Modifier la configuration système',
        'modules.view' => 'Voir les modules installés',
        'modules.manage' => 'Gérer les modules',
        'modules.install' => 'Installer de nouveaux modules',
        'modules.uninstall' => 'Désinstaller des modules',
        'auth.login' => 'Se connecter',
        'auth.register' => 'S\'inscrire',
        'auth.logout' => 'Se déconnecter',
        'admin' => 'Accès administrateur',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions spéciales
    |--------------------------------------------------------------------------
    */
    'special_permissions' => [
        '*' => ['super_admin'],
        'modules.install' => ['admin', 'super_admin'],
        'modules.uninstall' => ['admin', 'super_admin'],
        'config.edit' => ['admin', 'super_admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 86400,
        'prefix' => 'perm_',
    ],
];

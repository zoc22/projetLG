<?php

/**
 * Configuration du module Statistics
 * Ce module gère le tracking des visites et les agrégations de KPIs (Taux de conversion, etc.)
 */
return [
    'name' => 'Statistics',

    /*
     * Paramètres de rétention des logs de visite brute
     */
    'retention_days' => 90,

    /*
     * Configuration des KPIs par défaut
     */
    'metrics' => [
        'conversion_rate' => [
            'enabled' => true,
            'target_percent' => 10.0,
        ],
        'weekly_growth' => [
            'enabled' => true,
        ]
    ]
];

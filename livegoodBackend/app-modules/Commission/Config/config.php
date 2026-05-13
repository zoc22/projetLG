<?php

return [
    'name' => 'Commission',

    /**
     * Configuration des Bonus (Basée sur le Plan de Compensation LiveGood)
     */
    'bonuses' => [
        'fast_start' => [
            'enrollment_fee' => 49.95,
            'direct_percent' => 50.00, // 25$ pour un direct
            'levels' => [
                'UNRANKED' => [50],
                'BRONZE'   => [50, 10],
                'SILVER'   => [50, 10, 5, 5],
                'GOLD'     => [50, 10, 5, 5, 3, 2],
                'PLATINUM' => [50, 10, 5, 5, 3, 2, 2, 1],
                'DIAMOND'  => [50, 10, 5, 5, 3, 2, 2, 1, 1, 1],
            ],
        ],

        'matrix' => [
            'commission_per_member' => 0.25,
            'subscription_cost' => 9.95,
            'max_levels' => [
                'UNRANKED' => 12,
                'BRONZE'   => 13,
                'SILVER'   => 13,
                'GOLD'     => 14,
                'PLATINUM' => 14,
                'DIAMOND'  => 15,
            ],
        ],

        'matching' => [
            'direct_match_percent' => 50.00,
            'generations' => [
                'SILVER'   => [10, 10],
                'GOLD'     => [5, 5, 5],
                'PLATINUM' => [5, 5, 5, 5],
                'DIAMOND'  => [3, 3, 3, 3, 3],
            ],
        ],

        'retail' => [
            'base_percent' => 50.00, // 50% de la différence prix public / membre
        ],

        'influencer' => [
            'thresholds' => [
                ['sales' => 2500,  'extra' => 10], // Total 60%
                ['sales' => 5000,  'extra' => 20], // Total 70%
                ['sales' => 10000, 'extra' => 30], // Total 80%
                ['sales' => 25000, 'extra' => 40], // Total 90%
                ['sales' => 50000, 'extra' => 50], // Total 100%
            ],
        ],

        'diamond_pool' => [
            'company_revenue_percent' => 2.00,
        ],
    ],
];

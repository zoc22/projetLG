<?php

return [
    'name' => 'Rank',

    /**
     * Configuration des critères de qualification (LiveGood)
     */
    'qualifications' => [
        'bronze' => [
            'direct_active' => 2,
            'total_active'  => 0,
        ],
        'silver' => [
            'option_1' => ['direct_active' => 10],
            'option_2' => ['bronze_legs' => 3],
            'total_active' => 20,
        ],
        'gold' => [
            'option_1' => ['direct_active' => 30],
            'option_2' => ['silver_legs' => 3],
            'total_active' => 100,
        ],
        'platinum' => [
            'option_1' => ['direct_active' => 100],
            'option_2' => ['gold_legs' => 3],
            'total_active' => 500,
        ],
        'diamond' => [
            'platinum_legs' => 3,
            'total_active' => 2500,
        ],
    ]
];

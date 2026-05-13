<?php

return [
    'name' => 'Genealogy',

    /*
     * Matrix configuration (2x15 forced matrix)
     */
    'matrix' => [
        'width' => 2,
        'depth' => 15,
        'commission_per_member' => 0.25, // $0.25 par membre
        'subscription_cost' => 9.95,     // Coût mensuel
    ],

    /*
     * Rank requirements for depth unlocking in the matrix
     */
    'ranks' => [
        'UNRANKED' => ['max_depth' => 12],
        'BRONZE'   => ['max_depth' => 13],
        'SILVER'   => ['max_depth' => 13],
        'GOLD'     => ['max_depth' => 14],
        'PLATINUM' => ['max_depth' => 14],
        'DIAMOND'  => ['max_depth' => 15],
    ]
];

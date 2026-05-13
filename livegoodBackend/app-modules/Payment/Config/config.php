<?php

return [
    'name' => 'Payment',

    /*
     * Seuils de retrait minimum (LiveGood style)
     */
    'withdrawal' => [
        'min_amount' => 20.00,
        'processing_fee' => 1.00,
        'weekly_payout_day' => 4, // Jeudi
    ],

    /*
     * Configuration des Gateways
     */
    'gateways' => [
        'crypto' => [
            'provider' => 'bitpay', // Exemple
            'enabled' => true,
        ],
        'ewallet' => [
            'provider' => 'global-payout',
            'enabled' => true,
        ],
        'bank' => [
            'enabled' => true,
        ]
    ]
];

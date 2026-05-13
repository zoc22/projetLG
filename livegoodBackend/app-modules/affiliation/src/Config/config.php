<?php

namespace Modules\Affiliation\Config;

/**
 * Configuration du module Affiliation.
 */
return [
    'name' => 'Affiliation',

    // Frais Inscription (One time)
    'affiliate_fee' => 40.00,

    // Plans d'abonnement
    'plans' => [
        'monthly' => [
            'price' => 9.95,
            'label' => 'Monthly Membership'
        ],
        'annual' => [
            'price' => 99.00, // Souvent offert avec réduction, ex: 139$ total inscription incluse
            'label' => 'Annual Membership'
        ]
    ]
];

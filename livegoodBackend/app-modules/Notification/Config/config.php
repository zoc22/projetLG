<?php

return [
    'name' => 'Notification',
    'channels' => [
        'database' => true,
        'mail'     => true,
        'sms'      => false,
    ],
    'templates' => [
        'welcome' => 'Bienvenue chez LiveGood, :name !',
        'commission_paid' => 'Bonne nouvelle ! Vous avez reçu une commission de :amount $.',
        'new_referral'    => 'Un nouveau membre (:name) vient de rejoindre votre équipe !',
        'rank_up'         => 'Félicitations ! Vous avez atteint le rang :rank.',
    ]
];

<?php return array (
  'internachi/modular' => 
  array (
    'aliases' => 
    array (
      'Modules' => 'InterNACHI\\Modular\\Support\\Facades\\Modules',
    ),
    'providers' => 
    array (
      0 => 'InterNACHI\\Modular\\Support\\ModularServiceProvider',
      1 => 'InterNACHI\\Modular\\Support\\ModularizedCommandsServiceProvider',
    ),
  ),
  'laravel/pail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Pail\\PailServiceProvider',
    ),
  ),
  'laravel/sail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sail\\SailServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/socialite' => 
  array (
    'aliases' => 
    array (
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
    'providers' => 
    array (
      0 => 'Laravel\\Socialite\\SocialiteServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'modules/authentication' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Authentication\\Providers\\AuthenticationServiceProvider',
    ),
  ),
  'modules/commission' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Commission\\Providers\\CommissionServiceProvider',
    ),
  ),
  'modules/core' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Core\\Providers\\CoreServiceProvider',
      1 => 'Modules\\Core\\Providers\\ModuleServiceProvider',
    ),
  ),
  'modules/ecommerce' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Ecommerce\\Providers\\EcommerceServiceProvider',
    ),
  ),
  'modules/genealogy' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Genealogy\\Providers\\GenealogyServiceProvider',
    ),
  ),
  'modules/notification' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Notification\\Providers\\NotificationServiceProvider',
    ),
  ),
  'modules/payment' => 
  array (
    'providers' => 
    array (
      0 => 'Modules\\Payment\\Providers\\PaymentServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'nunomaduro/termwind' => 
  array (
    'providers' => 
    array (
      0 => 'Termwind\\Laravel\\TermwindServiceProvider',
    ),
  ),
);
<?php

return [
    App\Providers\AppServiceProvider::class,
    Modules\Core\Providers\CoreServiceProvider::class,
    Modules\Core\Providers\ModuleServiceProvider::class,
    Modules\Affiliation\Providers\AffiliationServiceProvider::class,
    Modules\UserManagement\Providers\UserManagementServiceProvider::class,
    Modules\Training\Providers\TrainingServiceProvider::class,
    Modules\Genealogy\Providers\GenealogyServiceProvider::class,
    Modules\Ecommerce\Providers\EcommerceServiceProvider::class,
    Modules\Support\Providers\SupportServiceProvider::class,
    Modules\Shop\Providers\ShopServiceProvider::class,
    Modules\Statistics\Providers\StatisticsServiceProvider::class,
    Modules\Rank\Providers\RankServiceProvider::class,
    Modules\Commission\Providers\CommissionServiceProvider::class,
    Modules\Payment\Providers\PaymentServiceProvider::class,
    Modules\Notification\Providers\NotificationServiceProvider::class,
    Modules\Marketing\Providers\MarketingServiceProvider::class,
];

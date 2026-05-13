<?php
$files = [
    "app-modules/Commission/Providers/CommissionServiceProvider.php",
    "app-modules/genealogy/Providers/GenealogyServiceProvider.php",
    "app-modules/Payment/Providers/PaymentServiceProvider.php",
    "app-modules/Marketing/src/Providers/MarketingServiceProvider.php",
    "app-modules/Notification/src/Providers/NotificationServiceProvider.php",
    "app-modules/Rank/src/Providers\RankServiceProvider.php",
    "app-modules/Shop/src/Providers/ShopServiceProvider.php",
    "app-modules/Statistics/src/Providers/StatisticsServiceProvider.php",
    "app-modules/Support/src/Providers/SupportServiceProvider.php",
    "app-modules/Training/src/Providers/TrainingServiceProvider.php",
    "app-modules/user-management/src/Providers/UserManagementServiceProvider.php"
];

$replacement = "        if (file_exists(__DIR__.'/../Routes/api.php')) {\n            \\Illuminate\\Support\\Facades\\Route::prefix('api')->middleware('api')->group(__DIR__.'/../Routes/api.php');\n        }";

foreach ($files as $f) {
    $path = str_replace('\\', '/', $f);
    if (file_exists($path)) {
        $content = file_get_contents($path);
        // We look for \$this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $content = preg_replace('/\\$this->loadRoutesFrom\\(__DIR__\\.\\\'\\/\\.\\.\\/Routes\\/api\\.php\\\'\\);/', ltrim($replacement), $content);
        file_put_contents($path, $content);
        echo "Updated $path\n";
    }
}

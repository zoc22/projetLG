<?php

namespace Modules\Marketing\Providers;

use Illuminate\Support\ServiceProvider;

class MarketingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // On pourrait ajouter des configs ici
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        if (file_exists(__DIR__.'/../Routes/api.php')) {
            \Illuminate\Support\Facades\Route::prefix('api')->middleware('api')->group(__DIR__.'/../Routes/api.php');
        }
    }
}


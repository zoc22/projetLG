<?php

namespace Modules\Commission\Providers;

use Illuminate\Support\ServiceProvider;

class CommissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'commission');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        if (file_exists(__DIR__.'/../Routes/api.php')) {
            \Illuminate\Support\Facades\Route::prefix('api')->middleware('api')->group(__DIR__.'/../Routes/api.php');
        }
    }
}


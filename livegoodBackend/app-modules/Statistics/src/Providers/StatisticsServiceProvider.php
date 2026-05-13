<?php

namespace Modules\Statistics\Providers;

use Illuminate\Support\ServiceProvider;

class StatisticsServiceProvider extends ServiceProvider
{
    /**
     * Enregistrement des configurations.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'statistics');
    }

    /**
     * Chargement des ressources du module.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        if (file_exists(__DIR__.'/../Routes/api.php')) {
            \Illuminate\Support\Facades\Route::prefix('api')->middleware('api')->group(__DIR__.'/../Routes/api.php');
        }
    }
}


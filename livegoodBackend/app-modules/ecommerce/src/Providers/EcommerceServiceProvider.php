<?php

namespace Modules\Ecommerce\Providers;

use Illuminate\Support\ServiceProvider;

class EcommerceServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}
	
	public function boot(): void
	{
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        if (file_exists(__DIR__.'/../../routes/api.php')) {
            \Illuminate\Support\Facades\Route::prefix('api')
                ->middleware('api')
                ->group(__DIR__.'/../../routes/api.php');
        }
	}
}

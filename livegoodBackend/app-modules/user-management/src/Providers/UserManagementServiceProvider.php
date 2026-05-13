<?php

namespace Modules\UserManagement\Providers;

use Illuminate\Support\ServiceProvider;

class UserManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (file_exists(__DIR__.'/../Routes/api.php')) {
            if (file_exists(__DIR__.'/../Routes/api.php')) {
            \Illuminate\Support\Facades\Route::prefix('api')->middleware('api')->group(__DIR__.'/../Routes/api.php');
        }
        }
    }
}


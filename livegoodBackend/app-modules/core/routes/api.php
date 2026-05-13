<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ModuleController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\HealthController;

/**
 * Routes API du Module Core
 *
 * Toutes les routes sont préfixées par /api/core
 * et utilisent le middleware 'api'
 */

Route::prefix('core')->middleware('api')->group(function () {
    // Routes de santé
    Route::get('/health', [HealthController::class, 'health'])->name('core.health');

    // Routes de configuration
    Route::get('/config', [HealthController::class, 'getCoreConfig'])->name('core.config');
    Route::get('/permissions/available', [HealthController::class, 'getAvailablePermissions'])->name('core.permissions.available');
    Route::get('/roles', [HealthController::class, 'getRolesInfo'])->name('core.roles');
    Route::get('/roles/{role}', [HealthController::class, 'getRoleDetails'])->name('core.role.details');

    // Routes de modules
    Route::prefix('modules')->group(function () {
        Route::get('/', [ModuleController::class, 'listModules'])->name('core.modules.list');
        Route::get('/{module}', [ModuleController::class, 'getModuleDetails'])->name('core.module.details');
        Route::get('/{module}/status', [ModuleController::class, 'checkModuleStatus'])->name('core.module.status');

        // Test des helpers de module
        Route::post('/test/helper-path', [ModuleController::class, 'testHelperModulePath'])->name('core.test.helper.module_path');
        Route::post('/test/helper-config', [ModuleController::class, 'testHelperModuleConfig'])->name('core.test.helper.module_config');
        Route::post('/test/helper-enabled', [ModuleController::class, 'testHelperModuleEnabled'])->name('core.test.helper.module_enabled');
    });

    // Routes de permissions (Authentification requise)
    Route::prefix('permissions')->middleware('auth:sanctum')->group(function () {
        Route::post('/check', [PermissionController::class, 'checkPermission'])->name('core.permission.check');
        Route::post('/check-all', [PermissionController::class, 'checkAllPermissions'])->name('core.permission.check.all');
        Route::post('/check-any', [PermissionController::class, 'checkAnyPermission'])->name('core.permission.check.any');
        Route::post('/check-role', [PermissionController::class, 'checkRole'])->name('core.permission.check.role');
        Route::get('/check-super-admin', [PermissionController::class, 'checkSuperAdmin'])->name('core.permission.check.super_admin');
        Route::get('/user', [PermissionController::class, 'getUserPermissions'])->name('core.permission.user');

        // Test des helpers de permissions
        Route::post('/test/helper-has-permission', [PermissionController::class, 'testHelperHasPermission'])->name('core.test.helper.has_permission');
        Route::get('/test/helper-is-admin', [PermissionController::class, 'testHelperIsAdmin'])->name('core.test.helper.is_admin');
    });

    // Routes de helpers généraux
    Route::prefix('helpers')->group(function () {
        Route::get('/uuid/generate', [HealthController::class, 'testGenerateUuid'])->name('core.helper.generate_uuid');
        Route::post('/currency/format', [HealthController::class, 'testFormatCurrency'])->name('core.helper.format_currency');
        Route::post('/cache/remember-forever', [HealthController::class, 'testCacheRememberForever'])->name('core.helper.cache_remember_forever');
        Route::post('/module-asset', [HealthController::class, 'testModuleAsset'])->name('core.helper.module_asset');
    });
});

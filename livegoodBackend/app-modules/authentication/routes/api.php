<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthController;
use Modules\Authentication\Http\Controllers\PasswordController;
use Modules\Authentication\Http\Controllers\TwoFactorController;
use Modules\Authentication\Http\Middleware\Authenticate;
use Modules\Authentication\Http\Middleware\TwoFactorMiddleware;

/**
 * Routes API pour le module d'authentification
 *
 * @package Modules\Authentication\Routes
 */

/*
 * Routes publiques (pas d'authentification requise)
 */
Route::prefix('auth')->group(function () {

    // Test route
    Route::get('/test', function () {
        return response()->json(['test' => 'ok']);
    });

    // Connexion et inscription
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('auth.login');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware(['api', 'throttle:5,1'])
        ->name('auth.register');

    // Mot de passe oublié
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])
        ->middleware('throttle:5,10')
        ->name('auth.forgot');

    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])
        ->middleware('throttle:5,10')
        ->name('auth.reset');

    // Vérification email
    Route::post('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.verify');

    Route::post('/resend-verification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware('throttle:3,5')
        ->name('verification.resend');

    // Challenge 2FA (pendant connexion)
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])
        ->middleware('throttle:5,1')
        ->name('2fa.verify');
});

/*
 * Routes protégées (authentification requise)
 */
Route::middleware(['auth:sanctum', Authenticate::class])->group(function () {

    Route::prefix('auth')->group(function () {

        // Déconnexion
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('auth.logout');

        // Utilisateur connecté
        Route::get('/me', [AuthController::class, 'me'])
            ->name('auth.me');

        // Rafraîchissement token
        Route::post('/refresh', [AuthController::class, 'refresh'])
            ->name('auth.refresh');

        // Changement de mot de passe
        Route::put('/change-password', [PasswordController::class, 'changePassword'])
            ->name('auth.change-password');

        // Routes 2FA (gestion)
        Route::prefix('2fa')->group(function () {
            Route::post('/enable', [TwoFactorController::class, 'enable'])
                ->name('2fa.enable');
            Route::post('/confirm', [TwoFactorController::class, 'confirm'])
                ->name('2fa.confirm');
            Route::post('/disable', [TwoFactorController::class, 'disable'])
                ->name('2fa.disable');
            Route::get('/recovery-codes', [TwoFactorController::class, 'getRecoveryCodes'])
                ->name('2fa.recovery-codes');
            Route::post('/regenerate-codes', [TwoFactorController::class, 'regenerateRecoveryCodes'])
                ->name('2fa.regenerate');
        });

        // Routes sensibles (2FA requise)
        Route::middleware([TwoFactorMiddleware::class])->group(function () {
            Route::get('/sensitive-data', [AuthController::class, 'getSensitiveData'])
                ->name('auth.sensitive');
        });
    });
});

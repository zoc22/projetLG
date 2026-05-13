<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/**
 * Routes web pour le module d'authentification
 * Pages de connexion, d'inscription, etc. pour les vues Blade
 *
 * @package Modules\Authentication\Routes
 */

Route::middleware(['web'])->group(function () {

    // Pages publiques
    Route::get('/login', function () {
        return view('authentication::auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('authentication::auth.register');
    })->name('register');

    Route::get('/forgot-password', function () {
        return view('authentication::auth.forgot-password');
    })->name('password.request');

    Route::get('/reset-password/{token}', function ($token) {
        return view('authentication::auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::get('/email/verify', function () {
        return view('authentication::auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/2fa/challenge', function () {
        return view('authentication::auth.2fa-challenge');
    })->name('2fa.challenge');

    // Routes protégées
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('authentication::dashboard');
        })->name('dashboard');

        Route::get('/profile', function () {
            return view('authentication::profile');
        })->name('profile');
    });
});
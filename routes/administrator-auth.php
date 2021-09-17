<?php

use App\Http\Controllers\Administrator\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Administrator\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Administrator\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Administrator\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Administrator\Auth\NewPasswordController;
use App\Http\Controllers\Administrator\Auth\PasswordResetLinkController;
use App\Http\Controllers\Administrator\Auth\RegisteredAdministratorController;
use App\Http\Controllers\Administrator\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::middleware(['guest:administrator'])->group(function () {

        Route::name('admin.')->group(function () {
            Route::get('/register', [RegisteredAdministratorController::class, 'create'])
                            ->name('register');
    
            Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                            ->name('login');
    
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                            ->name('password.request');
    
            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                            ->name('password.email');
        
            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                            ->name('password.reset');
        
            Route::post('/reset-password', [NewPasswordController::class, 'store'])
                            ->name('password.update');
        });
    
        Route::post('/register', [RegisteredAdministratorController::class, 'store']);
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    });

    Route::middleware(['auth:administrator'])->group(function () {

        Route::name('admin.')->group(function () {
            Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                            ->name('verification.notice');

            Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                            ->name('password.confirm');

            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                            ->name('logout');

            Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                            ->middleware(['signed', 'throttle:6,1'])
                            ->name('verification.verify');
            
            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                            ->middleware(['throttle:6,1'])
                            ->name('verification.send');
        });

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    });

});
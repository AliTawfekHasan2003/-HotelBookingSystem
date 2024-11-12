<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('lang')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::get('email/verify/{id}', 'verifyEmail')->name('verification.verify');
        Route::post('email/verify/resend', 'resendVerification');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth');
        Route::post('refresh', 'refresh')->middleware('auth');
    });

    Route::controller(AuthSocialController::class)->prefix('auth')->group(function () {
        Route::get('google', 'redirectToGoogle');
        Route::get('google/callback', 'googleCallback');
        Route::get('github', 'redirectToGithub');
        Route::get('github/callback', 'githubCallback');
    });

    Route::middleware('auth')->group(function () {
        Route::controller(NotificationController::class)->prefix('notification')->group(function () {
            Route::get('all', 'getAllNotifications');
            Route::get('unread', 'getUnreadNotifications');
            Route::put('markAsRead/{id}', 'markAsRead');
            Route::put('markAllAsRead', 'markAllAsRead');
        });
    });

    Route::middleware(['auth', 'role.user'])->group(function () {
        Route::controller(UserController::class)->prefix('user')->group(function () {
            Route::get('profile', 'show');
            Route::put('profile', 'updateProfile');
            Route::post('password', 'setPassword');
            Route::put('password', 'updatePassword');
        });
    });
});

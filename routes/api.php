<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\NotificationController;
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
        Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
            Route::get('', 'getAllNotifications');
            Route::get('unread', 'getUnreadNotifications');
            Route::patch('mark_as_read/{id}', 'markAsRead');
            Route::patch('mark_all_as_read', 'markAllAsRead');
        });
    });
});

require __DIR__  . '/api/user.php';
require __DIR__  . '/api/admin.php';
require __DIR__  . '/api/super_admin.php';

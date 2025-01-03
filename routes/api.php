<?php

use App\Http\Controllers\Api\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Api\Admin\RoomTypeController as AdminRoomTypeController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthSocialController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RoomTypeServiceController;
use App\Http\Controllers\Api\SuperAdmin\RoomController as SuperAdminRoomController;
use App\Http\Controllers\Api\SuperAdmin\RoomTypeController as SuperAdminRoomTypeController;
use App\Http\Controllers\Api\SuperAdmin\ServiceController as SuperAdminServiceController;
use App\Http\Controllers\Api\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\Api\User\RoomController;
use App\Http\Controllers\Api\User\RoomTypeController;
use App\Http\Controllers\Api\User\ServiceController;
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
        Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
            Route::get('', 'getAllNotifications');
            Route::get('unread', 'getUnreadNotifications');
            Route::patch('mark_as_read/{id}', 'markAsRead');
            Route::patch('mark_all_as_read', 'markAllAsRead');
        });
    });

    Route::middleware(['auth', 'role.user'])->prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('profile', 'showProfile');
            Route::patch('profile', 'updateProfile');
            Route::post('password', 'setPassword');
            Route::patch('password', 'updatePassword');
        });

        Route::controller(RoomTypeController::class)->prefix('room_types')->group(function () {
            Route::get('', 'index');
            Route::get('/favorite', 'getFavorite');
            Route::get('/{id}', 'show');
            Route::post('/{id}/favorite', 'markAsFavorite');
            Route::delete('/{id}/favorite', 'unmarkAsFavorite');
            Route::get('/{id}/rooms', 'rooms');
            Route::get('/{id}/services', 'services');
        });

        Route::controller(RoomController::class)->prefix('rooms')->group(function () {
            Route::get('', 'index');
            Route::get('/favorite', 'getFavorite');
            Route::get('/{id}', 'show');
            Route::post('/{id}/favorite', 'markAsFavorite');
            Route::delete('/{id}/favorite', 'unmarkAsFavorite');
        });

        Route::controller(ServiceController::class)->prefix('services')->group(function () {
            Route::get('', 'index');
            Route::get('/favorite', 'getFavorite');
            Route::get('/{id}', 'show');
            Route::post('/{id}/favorite', 'markAsFavorite');
            Route::delete('/{id}/favorite', 'unmarkAsFavorite');
            Route::get('/{id}/room_types', 'roomTypes');
        });
    });

    Route::middleware(['auth', 'role.admin'])->prefix('admin')->group(function () {
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('profile', 'showProfile');
            Route::patch('profile', 'updateProfile');
            Route::post('password', 'setPassword');
            Route::patch('password', 'updatePassword');
            Route::get('users', 'index');
            Route::get('users/{id}', 'showUser');
        });

        Route::controller(AdminRoomTypeController::class)->prefix('room_types')->group(function () {
            Route::get('/{id}/rooms', 'rooms');
            Route::get('/{id}/services', 'services');
        });
        Route::apiResource('room_types', AdminRoomTypeController::class);

        Route::apiResource('rooms', AdminRoomController::class);

        Route::get('services/{id}/room_types', [AdminServiceController::class, 'roomTypes']);
        Route::apiResource('services', AdminServiceController::class);

        Route::controller(RoomTypeServiceController::class)->prefix('room_type_services')->group(function () {
            Route::post('', 'store');
            Route::delete('', 'destroy');
        });
    });

    Route::middleware(['auth', 'role.super_admin'])->prefix('super_admin')->group(function () {
        Route::controller(SuperAdminUserController::class)->group(function () {
            Route::get('profile', 'showProfile');
            Route::patch('profile', 'updateProfile');
            Route::post('password', 'setPassword');
            Route::patch('password', 'updatePassword');
            Route::get('users', 'index');
            Route::get('users/{id}', 'showUser');
            Route::patch('users/{id}/assign_role', 'assignRole');
        });

        Route::controller(SuperAdminRoomTypeController::class)->prefix('room_types')->group(function () {
            Route::get('/trashed', 'trashedIndex');
            Route::get('/trashed/{id}', 'trashedShow');
            Route::patch('/trashed/{id}/restore', 'trashedRestore');
            Route::delete('/trashed/{id}/force', 'trashedForceDelete');
            Route::get('/{id}/rooms', 'rooms');
            Route::get('/{id}/services', 'services');
        });
        Route::apiResource('room_types', SuperAdminRoomTypeController::class);

        Route::controller(SuperAdminRoomController::class)->prefix('rooms')->group(function () {
            Route::get('/trashed', 'trashedIndex');
            Route::get('/trashed/{id}', 'trashedShow');
            Route::patch('/trashed/{id}/restore', 'trashedRestore');
            Route::delete('/trashed/{id}/force', 'trashedForceDelete');
        });
        Route::apiResource('rooms', SuperAdminRoomController::class);

        Route::controller(SuperAdminServiceController::class)->prefix('services')->group(function () {
            Route::get('/trashed', 'trashedIndex');
            Route::get('/trashed/{id}', 'trashedShow');
            Route::patch('/trashed/{id}/restore', 'trashedRestore');
            Route::delete('/trashed/{id}/force', 'trashedForceDelete');
            Route::get('/{id}/room_types', 'roomTypes');
        });
        Route::apiResource('services', SuperAdminServiceController::class);

        Route::controller(RoomTypeServiceController::class)->prefix('/room_type_services')->group(function () {
            Route::post('', 'store');
            Route::delete('', 'destroy');
        });
    });
});

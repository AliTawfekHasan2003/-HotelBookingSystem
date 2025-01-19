<?php

use App\Http\Controllers\Api\Admin\RoomController;
use App\Http\Controllers\Api\Admin\RoomTypeController;
use App\Http\Controllers\Api\Admin\ServiceController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\RoomTypeServiceController;
use Illuminate\Support\Facades\Route;


Route::middleware(['lang', 'auth', 'role.admin'])->prefix('admin')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
        Route::post('password', 'setPassword');
        Route::patch('password', 'updatePassword');
        Route::get('users', 'index');
        Route::get('users/{id}', 'showUser');
    });

    Route::controller(RoomTypeController::class)->prefix('room_types')->group(function () {
        Route::get('/{id}/rooms', 'rooms');
        Route::get('/{id}/services', 'services');
    });
    Route::apiResource('room_types', RoomTypeController::class);

    Route::get('rooms/{id}/unavailable_dates', [RoomController::class, 'unavailableDates']);
    Route::apiResource('rooms', RoomController::class);

    Route::controller(ServiceController::class)->prefix('services')->group(function () {
        Route::get('/{id}/available_units', 'limitedUnits');
        Route::get('/{id}/unavailable_dates', 'unavailableDates');
        Route::get('/{id}/room_types', 'roomTypes');
    });
    Route::apiResource('services', ServiceController::class);

    Route::controller(RoomTypeServiceController::class)->prefix('room_type_services')->group(function () {
        Route::post('', 'store');
        Route::delete('', 'destroy');
    });
});

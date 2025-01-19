<?php

use App\Http\Controllers\Api\RoomTypeServiceController;
use App\Http\Controllers\Api\SuperAdmin\InvoiceController;
use App\Http\Controllers\Api\SuperAdmin\RoomController;
use App\Http\Controllers\Api\SuperAdmin\RoomTypeController;
use App\Http\Controllers\Api\SuperAdmin\ServiceController;
use App\Http\Controllers\Api\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role.super_admin'])->prefix('super_admin')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
        Route::post('password', 'setPassword');
        Route::patch('password', 'updatePassword');
        Route::get('users', 'index');
        Route::get('users/{id}', 'showUser');
        Route::patch('users/{id}/assign_role', 'assignRole');
    });

    Route::controller(RoomTypeController::class)->prefix('room_types')->group(function () {
        Route::get('/trashed', 'trashedIndex');
        Route::get('/trashed/{id}', 'trashedShow');
        Route::patch('/trashed/{id}/restore', 'trashedRestore');
        Route::delete('/trashed/{id}/force', 'trashedForceDelete');
        Route::get('/{id}/rooms', 'rooms');
        Route::get('/{id}/services', 'services');
    });
    Route::apiResource('room_types', RoomTypeController::class);

    Route::controller(RoomController::class)->prefix('rooms')->group(function () {
        Route::get('/trashed', 'trashedIndex');
        Route::get('/trashed/{id}', 'trashedShow');
        Route::patch('/trashed/{id}/restore', 'trashedRestore');
        Route::delete('/trashed/{id}/force', 'trashedForceDelete');
        Route::get('/{id}/unavailable_dates', 'unavailableDates');
        Route::get('/{id}/bookings', 'bookings');
    });
    Route::apiResource('rooms', RoomController::class);

    Route::controller(ServiceController::class)->prefix('services')->group(function () {
        Route::get('/trashed', 'trashedIndex');
        Route::get('/trashed/{id}', 'trashedShow');
        Route::patch('/trashed/{id}/restore', 'trashedRestore');
        Route::delete('/trashed/{id}/force', 'trashedForceDelete');
        Route::get('/{id}/room_types', 'roomTypes');
        Route::get('/{id}/unavailable_dates', 'unavailableDates');
        Route::get('/{id}/available_units', 'limitedUnits');
        Route::get('/{id}/bookings', 'bookings');
    });
    Route::apiResource('services', ServiceController::class);

    Route::controller(RoomTypeServiceController::class)->prefix('/room_type_services')->group(function () {
        Route::post('', 'store');
        Route::delete('', 'destroy');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('', 'index');
        Route::get('/{id}', 'show');
        Route::get('/{id}/bookings', 'bookings');
    });
});

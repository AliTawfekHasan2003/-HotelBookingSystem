<?php

use App\Http\Controllers\Api\User\BookingController;
use App\Http\Controllers\Api\User\InvoiceController;
use App\Http\Controllers\Api\User\RoomController;
use App\Http\Controllers\Api\User\RoomTypeController;
use App\Http\Controllers\Api\User\ServiceController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['lang', 'auth', 'role.user'])->prefix('user')->group(function () {
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
        Route::get('/{id}/unavailable_dates', 'unavailableDates');
    });

    Route::controller(ServiceController::class)->prefix('services')->group(function () {
        Route::get('', 'index');
        Route::get('/favorite', 'getFavorite');
        Route::get('/{id}', 'show');
        Route::post('/{id}/favorite', 'markAsFavorite');
        Route::delete('/{id}/favorite', 'unmarkAsFavorite');
        Route::get('/{id}/room_types', 'roomTypes');
        Route::get('/{id}/available_units', 'limitedUnits');
    });

    Route::controller(BookingController::class)->prefix('bookings')->group(function () {
        Route::post('/calculate_cost', 'calculateCost');
        Route::post('/payment_intent', 'paymentIntent')->name('payment_intent');
        Route::post('/confirm_payment', 'confirmPayment');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('', 'index');
        Route::get('/{id}', 'show');
        Route::get('/{id}/bookings', 'bookings');
    });
});

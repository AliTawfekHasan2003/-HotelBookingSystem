<?php

use App\Http\Controllers\Api\AuthController;
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
});

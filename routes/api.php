<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentNotificationController;
use App\Http\Controllers\Api\Admin\CinemaController as AdminCinemaController;
use App\Http\Controllers\Api\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Api\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Api\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Api\Admin\SeatController as AdminSeatController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register-attempts');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login-attempts');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/schedules/{schedule}/seats', [BookingController::class, 'availableSeats']);
    Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:booking-attempts');
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/checkout', [PaymentController::class, 'checkout'])->middleware('throttle:booking-attempts');
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
    Route::apiResource('movies', AdminMovieController::class);
    Route::apiResource('cinemas', AdminCinemaController::class);
    Route::apiResource('schedules', AdminScheduleController::class);
    Route::apiResource('studios', AdminStudioController::class);
    Route::get('studios/{studio}/seats', [AdminSeatController::class, 'index']);
    Route::post('studios/{studio}/seats/generate', [AdminSeatController::class, 'generate']);
    Route::delete('seats/{seat}', [AdminSeatController::class, 'destroy']);
});

Route::post('payment/notification', [PaymentNotificationController::class, 'handle']);

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);
Route::get('/cinemas', [CinemaController::class, 'index']);
Route::get('/cinemas/{cinema}', [CinemaController::class, 'show']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
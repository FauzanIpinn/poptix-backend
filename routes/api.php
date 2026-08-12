<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login-attempts');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/schedules/{schedule}/seats', [BookingController::class, 'availableSeats']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);
Route::get('/cinemas', [CinemaController::class, 'index']);
Route::get('/cinemas/{cinema}', [CinemaController::class, 'show']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
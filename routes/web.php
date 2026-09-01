<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;            
use App\Http\Controllers\MovieCatalogController;
use App\Http\Controllers\Api\PaymentNotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/movies', [MovieCatalogController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieCatalogController::class, 'show'])->name('movies.show');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('movies', MovieController::class);
    Route::resource('cinemas', CinemaController::class);
    Route::resource('schedules', ScheduleController::class);
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/schedules/{schedule}/seats', [BookingController::class, 'showSeats'])->name('schedules.seats');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.index');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/checkout', [PaymentController::class, 'checkout'])->name('bookings.checkout');
});

Route::post('/payment/notification', [PaymentNotificationController::class, 'handle'])->name('payment.notification');

require __DIR__ . '/auth.php';
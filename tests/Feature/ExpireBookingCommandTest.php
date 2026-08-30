<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpireBookingCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_pending_yang_lewat_waktu_jadi_expired(): void {
        $schedule = Schedule::factory()->create([
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
        ]);
        $user = User::factory()->create();

        $expiredBooking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'pending',
            'expires_at' => now()->subMinutes(5),
        ]);

        $stillValidBooking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->artisan('bookings:expire')->assertSuccessful();

        $expired = $expiredBooking->fresh();
        $stillValid = $stillValidBooking->fresh();

        $this->assertEquals('expired', $expired->status);
        $this->assertEquals('pending', $stillValid->status);
    }

    public function test_booking_yang_sudah_dibayar_tidak_ikut_di_expire(): void {
        $schedule = Schedule::factory()->create([
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
        ]);
        $user = User::factory()->create();

        $paidBooking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'paid',
            'expires_at' => now()->subMinutes(30), // lewat waktu, tapi sudah paid
        ]);

        $this->artisan('bookings:expire')->assertSuccessful();

        $this->assertEquals('paid', $paidBooking->fresh()->status);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_tidak_bisa_lihat_booking_milik_orang_lain(): void {
        $schedule = Schedule::factory()->create([
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
        ]);

        $owner = User::factory()->create();
        $owner->assignRole('user');
        $stranger = User::factory()->create();
        $stranger->assignRole('user');

        $booking = Booking::factory()->create([
            'user_id' => $owner->id,
            'schedule_id' => $schedule->id,
        ]);

        $this->actingAs($stranger, 'sanctum')
            ->getJson("/api/bookings/{$booking->id}")
            ->assertStatus(403);

        $this->actingAs($owner, 'sanctum')
            ->getJson("/api/bookings/{$booking->id}")
            ->assertStatus(200);
    }

    public function test_booking_yang_sudah_paid_tidak_bisa_dibatalkan(): void {
        $schedule = Schedule::factory()->create([
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
        ]);

        $user = User::factory()->create();
        $user->assignRole('user');

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'paid',
        ]);

        $this->actingAs($user, 'sanctum')
            ->patchJson("/api/bookings/{$booking->id}/cancel")
            ->assertStatus(403);
    }
}
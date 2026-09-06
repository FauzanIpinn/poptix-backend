<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPastScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_jadwal_yang_sudah_lewat_ditolak_dengan_422(): void {
        $movie = Movie::factory()->create(['duration' => 120]);
        $cinema = Cinema::factory()->create();
        $studio = $cinema->studios()->first();
        $seat = $studio->seats()->first();

        // Jadwal kemarin
        $pastSchedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'studio_id' => $studio->id,
            'show_date' => now()->subDay()->format('Y-m-d'),
            'show_time' => '10:00:00',
            'price' => 50000,
        ]);

        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/bookings', [
            'schedule_id' => $pastSchedule->id,
            'seat_ids' => [$seat->id],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Jadwal ini sudah dimulai, tidak bisa dibooking.',
            ]);
    }
}

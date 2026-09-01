<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRaceConditionTest extends TestCase
{
    use RefreshDatabase;

    protected function createScheduleWithCinema(array $scheduleOverrides = []): array {
        $movie = Movie::factory()->create(['duration' => 120]);
        $cinema = Cinema::factory()->create();
        $studio = $cinema->studios()->first();

        $schedule = Schedule::factory()->create(array_merge([
            'movie_id' => $movie->id,
            'studio_id' => $studio->id,
            'price' => 50000,
        ], $scheduleOverrides));

        return [$cinema, $studio, $schedule];
    }

    public function test_dua_user_tidak_bisa_booking_kursi_yang_sama(): void {
        [$cinema, $studio, $schedule] = $this->createScheduleWithCinema();
        $seat = $studio->seats()->first();

        $userA = User::factory()->create();
        $userA->assignRole('user');
        $userB = User::factory()->create();
        $userB->assignRole('user');

        $this->actingAs($userA, 'sanctum')->postJson('/api/bookings', [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
        ])->assertStatus(201);

        $this->actingAs($userB, 'sanctum')->postJson('/api/bookings', [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
        ])->assertStatus(409);

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_booking_yang_dibatalkan_bisa_dipesan_ulang(): void {
        [$cinema, $studio, $schedule] = $this->createScheduleWithCinema();
        $seat = $studio->seats()->first();

        $user = User::factory()->create();
        $user->assignRole('user');

        $first = $this->actingAs($user, 'sanctum')->postJson('/api/bookings', [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
        ])->assertStatus(201);

        $bookingId = $first->json('data.id');

        $this->actingAs($user, 'sanctum')
            ->patchJson("/api/bookings/{$bookingId}/cancel")
            ->assertStatus(200);

        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
        ])->assertStatus(201);
    }

    public function test_double_submit_dengan_idempotency_key_tidak_duplikat(): void {
        [$cinema, $studio, $schedule] = $this->createScheduleWithCinema();
        $seat = $studio->seats()->first();

        $user = User::factory()->create();
        $user->assignRole('user');

        $payload = [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
            'idempotency_key' => 'test-key-123',
        ];

        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', $payload)->assertStatus(201);
        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', $payload)->assertStatus(201);

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_kursi_dari_studio_lain_ditolak(): void {
        [, , $schedule] = $this->createScheduleWithCinema();

        $otherCinema = Cinema::factory()->create();
        $foreignSeat = $otherCinema->studios()->first()->seats()->first();

        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$foreignSeat->id],
        ])->assertStatus(422)
          ->assertJsonValidationErrors('seat_ids');
    }
}
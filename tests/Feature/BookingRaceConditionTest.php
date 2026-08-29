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

    public function test_dua_user_tidak_bisa_booking_kursi_yang_sama(): void {
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create(); // observer otomatis bikin kursi
        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'price' => 50000,
        ]);
        $seat = $cinema->seats()->first();

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
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create();
        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'price' => 50000,
        ]);
        $seat = $cinema->seats()->first();

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
        ])->assertStatus(201); // regression test untuk fix constraint unique
    }

    public function test_double_submit_dengan_idempotency_key_tidak_duplikat(): void {
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create();
        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'price' => 50000,
        ]);
        $seat = $cinema->seats()->first();

        $user = User::factory()->create();
        $user->assignRole('user');

        $payload = [
            'schedule_id' => $schedule->id,
            'seat_ids' => [$seat->id],
            'idempotency_key' => 'test-key-123',
        ];

        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', $payload)->assertStatus(201);
        $this->actingAs($user, 'sanctum')->postJson('/api/bookings', $payload)->assertStatus(201);

        $this->assertDatabaseCount('bookings', 1); // bukan 2
    }
}
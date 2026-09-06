<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function makePaidBooking(): array
    {
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create();
        $studio = $cinema->studios()->first();
        $seat = $studio->seats()->first();

        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'studio_id' => $studio->id,
            'price' => 50000,
        ]);

        $user = User::factory()->create();
        $user->assignRole('user');

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'total_price' => 50000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $booking->bookingSeats()->create([
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'price' => 50000,
        ]);

        return [$user, $booking];
    }

    public function test_halaman_verifikasi_tiket_menampilkan_data_tiket_valid(): void
    {
        [, $booking] = $this->makePaidBooking();

        $response = $this->get(route('tickets.verify', $booking->booking_code));

        $response->assertStatus(200);
        $response->assertSee('TIKET VALID & RESMI', false);
        $response->assertSee($booking->booking_code);
        $response->assertSee($booking->schedule->movie->title);
    }

    public function test_petugas_bisa_check_in_tiket_dan_mencatat_waktu(): void
    {
        [, $booking] = $this->makePaidBooking();

        $this->assertNull($booking->checked_in_at);

        $response = $this->post(route('tickets.checkin', $booking->booking_code));

        $response->assertRedirect();
        $fresh = $booking->fresh();
        $this->assertNotNull($fresh->checked_in_at);

        // Check-in kedua kali harus ditolak / diberi info sudah terpakai
        $secondResponse = $this->post(route('tickets.checkin', $booking->booking_code));
        $secondResponse->assertRedirect();
        $secondResponse->assertSessionHas('info');
    }

    public function test_tiket_pending_tidak_bisa_check_in(): void
    {
        $movie = Movie::factory()->create();
        $cinema = Cinema::factory()->create();
        $studio = $cinema->studios()->first();

        $schedule = Schedule::factory()->create([
            'movie_id' => $movie->id,
            'cinema_id' => $cinema->id,
            'studio_id' => $studio->id,
        ]);

        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('tickets.checkin', $booking->booking_code));

        $response->assertSessionHas('error');
        $this->assertNull($booking->fresh()->checked_in_at);
    }

    public function test_user_bisa_mengakses_halaman_cetak_tiket(): void
    {
        [$user, $booking] = $this->makePaidBooking();

        $response = $this->actingAs($user)->get(route('bookings.print', $booking));

        $response->assertStatus(200);
        $response->assertSee($booking->booking_code);
        $response->assertSee('POPTIX TICKET SYSTEM');
    }
}

<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BookingService
{
    public function createBooking(
        User $user,
        int $scheduleId,
        array $seatIds,
        ?string $idempotencyKey = null
    ): Booking {
        // Idempotency check — kembalikan booking yang sama jika kunci masih valid
        if ($idempotencyKey) {
            $cacheKey         = "booking-idempotency:{$user->id}:{$idempotencyKey}";
            $existingBookingId = Cache::get($cacheKey);
            if ($existingBookingId) {
                return Booking::findOrFail($existingBookingId);
            }
        }

        $booking = DB::transaction(function () use ($user, $scheduleId, $seatIds) {
            $schedule = Schedule::findOrFail($scheduleId);

            // Lock kursi untuk mencegah race condition
            $seats = Seat::whereIn('id', $seatIds)->lockForUpdate()->get();

            if ($seats->count() !== count($seatIds)) {
                throw new RuntimeException('Salah satu kursi yang dipilih tidak valid.');
            }

            // Validasi bahwa semua kursi memang milik cinema dari jadwal ini
            $invalidSeats = $seats->where('cinema_id', '!=', $schedule->cinema_id);
            if ($invalidSeats->isNotEmpty()) {
                throw new RuntimeException('Salah satu kursi yang dipilih tidak tersedia untuk jadwal ini.');
            }

            // Cek apakah ada kursi yang sudah dipesan
            $alreadyBooked = $schedule->bookingSeats()
                ->whereIn('seat_id', $seatIds)
                ->whereHas('booking', fn ($q) => $q->whereIn('status', ['pending', 'paid']))
                ->exists();

            if ($alreadyBooked) {
                throw new RuntimeException('Salah satu kursi yang kamu pilih baru saja dipesan orang lain.');
            }

            $booking = Booking::create([
                'user_id'     => $user->id,
                'schedule_id' => $schedule->id,
                'total_price' => $seats->count() * $schedule->price,
                'status'      => 'pending',
                'expires_at'  => now()->addMinutes(15),
            ]);

            // Batch insert booking seats untuk efisiensi (menghindari N queries)
            $bookingSeatData = $seats->map(fn ($seat) => [
                'booking_id'  => $booking->id,
                'schedule_id' => $schedule->id,
                'seat_id'     => $seat->id,
                'price'       => $schedule->price,
                'created_at'  => now(),
                'updated_at'  => now(),
            ])->toArray();

            $booking->bookingSeats()->insert($bookingSeatData);

            return $booking;
        });

        if ($idempotencyKey) {
            Cache::put(
                "booking-idempotency:{$user->id}:{$idempotencyKey}",
                $booking->id,
                now()->addMinutes(5)
            );
        }

        return $booking;
    }

    public function cancelBooking(Booking $booking): Booking
    {
        $booking->update(['status' => 'cancelled']);

        return $booking->fresh();
    }
}
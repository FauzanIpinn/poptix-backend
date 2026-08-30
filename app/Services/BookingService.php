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
    public function createBooking(User $user, int $scheduleId, array $seatIds, $idempotencyKey = null): Booking {
        if ($idempotencyKey) {
            $cacheKey = "booking-idempotency:{$user->id}:{$idempotencyKey}";
            $existingBookingId = Cache::get($cacheKey);
            if ($existingBookingId) {
                return Booking::findOrFail($existingBookingId);
            }
        }

        return DB::transaction(function () use ($user, $scheduleId, $seatIds) {
            $schedule = Schedule::findOrFail($scheduleId);

            $seats = Seat::whereIn('id', $seatIds)->lockForUpdate()->get();

            if ($seats->count() !== count($seatIds)) {
                throw new RuntimeException('Salah satu kursi yang dipilih tidak valid.');
            }

            $alreadyBooked = $schedule->bookingSeats()
                ->whereIn('seat_id', $seatIds)
                ->whereHas('booking', fn ($q) => $q->whereIn('status', ['pending', 'paid']))
                ->exists();

            if ($alreadyBooked) {
                throw new RuntimeException('Salah satu kursi yang kamu pilih baru saja dipesan orang lain.');
            }

            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'total_price' => $seats->count() * $schedule->price,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(15),
            ]);

            foreach ($seats as $seat) {
                $booking->bookingSeats()->create([
                    'schedule_id' => $schedule->id,
                    'seat_id' => $seat->id,
                    'price' => $schedule->price,
                ]);
            }

            return $booking;
        });
    }

    public function cancelBooking(Booking $booking): Booking {
        $booking->update(['status' => 'cancelled']);
        return $booking->fresh();
    }
}
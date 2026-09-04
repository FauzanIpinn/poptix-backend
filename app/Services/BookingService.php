<?php

namespace App\Services;

use App\Exceptions\BookingException;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookingService
{
    private const BOOKED_SEATS_CACHE_TTL_SECONDS = 10;
    private const STUDIO_SEATS_CACHE_TTL_HOURS = 6;

    public function getAvailableSeats(Schedule $schedule): Collection {
        $seats = Cache::remember(
            "studio:{$schedule->studio_id}:seats",
            now()->addHours(self::STUDIO_SEATS_CACHE_TTL_HOURS),
            fn () => Seat::where('studio_id', $schedule->studio_id)
                ->orderBy('row')
                ->orderBy('number')
                ->get()
        );

        $bookedSeatIds = Cache::remember(
            $this->bookedSeatsCacheKey($schedule->id),
            now()->addSeconds(self::BOOKED_SEATS_CACHE_TTL_SECONDS),
            fn () => BookingSeat::where('schedule_id', $schedule->id)
                ->whereHas('booking', fn ($q) => $q->active())
                ->pluck('seat_id')
                ->all()
        );

        return $seats->map(function (Seat $seat) use ($bookedSeatIds) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds, true);
            return $seat;
        });
    }

    public function createBooking(User $user, int $scheduleId, array $seatIds, ?string $idempotencyKey = null): Booking {
        if ($idempotencyKey) {
            $cacheKey = "booking-idempotency:{$user->id}:{$idempotencyKey}";
            $existingBookingId = Cache::get($cacheKey);
            if ($existingBookingId) {
                return Booking::findOrFail($existingBookingId);
            }
        }

        $booking = DB::transaction(function () use ($user, $scheduleId, $seatIds) {
            $schedule = Schedule::findOrFail($scheduleId);

            if ($schedule->hasStarted()) {
                throw BookingException::scheduleAlreadyStarted();
            }

            $seats = Seat::whereIn('id', $seatIds)->lockForUpdate()->get();

            if ($seats->count() !== count($seatIds)) {
                throw BookingException::seatInvalid();
            }

            $invalidSeats = $seats->where('studio_id', '!=', $schedule->studio_id);
            if ($invalidSeats->isNotEmpty()) {
                throw BookingException::seatNotInStudio();
            }

            $alreadyBooked = $schedule->bookingSeats()
                ->whereIn('seat_id', $seatIds)
                ->whereHas('booking', fn ($q) => $q->active())
                ->exists();

            if ($alreadyBooked) {
                throw BookingException::seatAlreadyTaken();
            }

            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'total_price' => $seats->count() * $schedule->price,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(config('booking.reservation_ttl_minutes')),
            ]);

            $bookingSeatData = $seats->map(fn (Seat $seat) => [
                'booking_id' => $booking->id,
                'schedule_id' => $schedule->id,
                'seat_id' => $seat->id,
                'price' => $schedule->price,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            $booking->bookingSeats()->insert($bookingSeatData);

            return $booking;
        });

        $this->forgetBookedSeatsCache($booking->schedule_id);

        if ($idempotencyKey) {
            Cache::put(
                "booking-idempotency:{$user->id}:{$idempotencyKey}",
                $booking->id,
                now()->addMinutes(config('booking.idempotency_ttl_minutes'))
            );
        }

        return $booking;
    }

    public function cancelBooking(Booking $booking): Booking {
        // Defense-in-depth: guard status juga di sini, bukan cuma di Policy layer,
        // supaya Service ini tetap aman kalau nanti dipanggil dari jalur lain
        // (mis. fitur admin bulk-cancel) yang mungkin lewat Policy berbeda.
        if ($booking->status !== 'pending') {
            throw BookingException::notPending();
        }

        $booking->update(['status' => 'cancelled']);
        $this->forgetBookedSeatsCache($booking->schedule_id);

        return $booking->fresh();
    }

    public function forgetBookedSeatsCache(int $scheduleId): void {
        Cache::forget($this->bookedSeatsCacheKey($scheduleId));
    }

    private function bookedSeatsCacheKey(int $scheduleId): string {
        return "schedule:{$scheduleId}:booked-seat-ids";
    }
}
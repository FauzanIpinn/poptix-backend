<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function showSeats(Schedule $schedule): View {
        $schedule->load(['movie', 'cinema.seats']);

        $bookedSeatIds = BookingSeat::where('schedule_id', $schedule->id)
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['pending', 'paid']);
            })
            ->pluck('seat_id')
            ->toArray();

        return view('schedules.seats', compact('schedule', 'bookedSeatIds'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse {
        $validated = $request->validated();

        try {
            $booking = DB::transaction(function () use ($validated, $request) {
                $schedule = Schedule::findOrFail($validated['schedule_id']);

                $seats = Seat::whereIn('id', $validated['seat_ids'])
                    ->lockForUpdate()
                    ->get();

                $alreadyBooked = BookingSeat::where('schedule_id', $schedule->id)
                    ->whereIn('seat_id', $validated['seat_ids'])
                    ->whereHas('booking', function ($query) {
                        $query->whereIn('status', ['pending', 'paid']);
                    })
                    ->exists();

                if ($alreadyBooked) {
                    throw new \Exception('Salah satu kursi yang kamu pilih baru saja dipesan orang lain. Silakan pilih kursi lain.');
                }

                $totalPrice = $seats->count() * $schedule->price;

                $booking = Booking::create([
                    'user_id' => $request->user()->id,
                    'schedule_id' => $schedule->id,
                    'total_price' => $totalPrice,
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
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat! Selesaikan pembayaran dalam 15 menit.');
    }

    public function show(Booking $booking): View {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return view('bookings.show', compact('booking'));
    }

    public function myBookings(): View {
        $bookings = Booking::where('user_id', auth()->user()->id)
            ->with(['schedule.movie', 'schedule.cinema'])
            ->latest()
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    public function cancel(Booking $booking): RedirectResponse {
        $this->authorize('cancel', $booking);

        $booking->update(['status' => 'cancelled']);

        return redirect()
        ->route('bookings.index')
        ->with('success', 'Booking berhasil dibatalkan.');
    }
}
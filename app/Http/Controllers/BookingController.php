<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

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

    public function __construct(protected BookingService $bookingService) {}

    public function store(StoreBookingRequest $request): RedirectResponse {
        $validated = $request->validated();

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $validated['schedule_id'],
                $validated['seat_ids'],
                $validated['idempotency_key'] ?? null,
            );
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat! Selesaikan pembayaran dalam 15 menit.');
    }

    public function show(Booking $booking): View {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return view('bookings.show', compact('booking'));
    }

    public function myBookings(): View {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['schedule.movie', 'schedule.cinema'])
            ->latest()
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    public function cancel(Booking $booking): RedirectResponse {
        $this->authorize('cancel', $booking);
        $this->bookingService->cancelBooking($booking);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}
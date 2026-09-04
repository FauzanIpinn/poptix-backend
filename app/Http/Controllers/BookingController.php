<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Schedule;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function showSeats(Schedule $schedule): View {
        $schedule->load(['movie', 'studio.cinema']);

        $seats = $this->bookingService->getAvailableSeats($schedule);

        return view('schedules.seats', compact('schedule', 'seats'));
    } 

    public function store(StoreBookingRequest $request): RedirectResponse {
        $validated = $request->validated();

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $validated['schedule_id'],
                $validated['seat_ids'],
                $validated['idempotency_key'] ?? null,
            );
        } catch (BookingException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('bookings.show', $booking)->with(
            'success',
            'Booking berhasil dibuat! Selesaikan pembayaran dalam ' . config('booking.reservation_ttl_minutes') . ' menit.'
        );
    }

    public function show(Booking $booking): View {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat']);

        return view('bookings.show', compact('booking'));
    }

    public function myBookings(): View {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat'])
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
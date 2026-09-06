<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketVerificationController extends Controller
{
    public function verify(string $bookingCode): View
    {
        abort_if(! auth()->user()?->hasRole('admin'), 403, 'Unauthorized action.');

        $booking = Booking::where('booking_code', $bookingCode)
            ->with(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat', 'user'])
            ->first();

        return view('tickets.verify', compact('booking', 'bookingCode'));
    }

    public function checkIn(Request $request, string $bookingCode): RedirectResponse
    {
        abort_if(! auth()->user()?->hasRole('admin'), 403, 'Unauthorized action.');

        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (! $booking) {
            return back()->with('error', 'Tiket tidak ditemukan.');
        }

        if ($booking->status !== 'paid') {
            return back()->with('error', 'Tiket belum dibayar / tidak valid.');
        }

        if ($booking->checked_in_at) {
            return back()->with('info', 'Tiket ini sudah pernah digunakan (Check-in pada ' . $booking->checked_in_at->format('d M Y, H:i') . ' WIB).');
        }

        $booking->update([
            'checked_in_at' => now(),
        ]);

        return back()->with('success', 'Check-in Berhasil! Penonton dipersilakan masuk ke studio.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Menginisiasi proses checkout dan mendapatkan Snap Token dari Midtrans.
     */
    public function checkout(Booking $booking): RedirectResponse|JsonResponse
    {
        $this->authorize('view', $booking);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Booking ini sudah tidak bisa dibayar.');
        }

        if ($booking->expires_at && $booking->expires_at->isPast()) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Waktu pembayaran booking ini sudah habis.');
        }

        try {
            $snapToken = $this->midtransService->createSnapToken($booking);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', $e->getMessage());
        }

        return response()->json([
            'snap_token' => $snapToken,
            'client_key' => config('midtrans.client_key'),
        ]);
    }
}
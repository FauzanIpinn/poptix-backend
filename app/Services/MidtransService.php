<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct() {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(Booking $booking): string {
        $booking->load(['user', 'schedule.movie', 'bookingSeats.seat']);

        $midtransOrderId = $booking->booking_code . '-' . now()->timestamp;

        $itemDetails = $booking->bookingSeats->map(function ($bookingSeat) use ($booking) {
            return [
                'id' => 'SEAT-' . $bookingSeat->seat->code,
                'price' => (int) $bookingSeat->price,
                'quantity' => 1,
                'name' => 'Tiket ' . $booking->schedule->movie->title . ' - Kursi ' . $bookingSeat->seat->code,
            ];
        })->toArray();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
            'expiry' => [
                'unit' => 'minutes',
                'duration' => 15,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $booking->update([
            'snap_token' => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return $snapToken;
    }
}
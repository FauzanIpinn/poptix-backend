<?php

namespace App\Services;

use App\Exceptions\BookingException;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable;

class MidtransService
{
    public function __construct() {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(Booking $booking): string {
        $booking->loadMissing(['user', 'schedule.movie', 'bookingSeats.seat']);

        $midtransOrderId = $booking->booking_code . '-' . now()->timestamp;

        $itemDetails = $booking->bookingSeats->map(fn ($bookingSeat) => [
            'id' => 'SEAT-' . $bookingSeat->seat->code,
            'price' => (int) $bookingSeat->price,
            'quantity' => 1,
            'name' => 'Tiket ' . $booking->schedule->movie->title . ' - Kursi ' . $bookingSeat->seat->code,
        ])->toArray();

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
                'duration' => config('booking.reservation_ttl_minutes'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (Throwable $e) {
            Log::error('Midtrans: Gagal membuat Snap Token.', [
                'booking_id' => $booking->id,
                'order_id' => $midtransOrderId,
                'error' => $e->getMessage(),
            ]);
            throw BookingException::gatewayFailure();
        }

        $booking->update([
            'snap_token' => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return $snapToken;
    }

    
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool {
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

        return hash_equals($expected, $signatureKey);
    }
}
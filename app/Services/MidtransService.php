<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use RuntimeException;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Membuat Snap Token untuk proses pembayaran.
     *
     * @throws RuntimeException jika Midtrans SDK gagal membuat token.
     */
    public function createSnapToken(Booking $booking): string
    {
        $booking->load(['user', 'schedule.movie', 'bookingSeats.seat']);

        $midtransOrderId = $booking->booking_code . '-' . now()->timestamp;

        $itemDetails = $booking->bookingSeats->map(function ($bookingSeat) use ($booking) {
            return [
                'id'       => 'SEAT-' . $bookingSeat->seat->code,
                'price'    => (int) $bookingSeat->price,
                'quantity' => 1,
                'name'     => 'Tiket ' . $booking->schedule->movie->title . ' - Kursi ' . $bookingSeat->seat->code,
            ];
        })->toArray();

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email'      => $booking->user->email,
            ],
            'expiry' => [
                'unit'     => 'minutes',
                'duration' => 15,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans: Gagal membuat Snap Token.', [
                'booking_id' => $booking->id,
                'order_id'   => $midtransOrderId,
                'error'      => $e->getMessage(),
            ]);
            throw new RuntimeException('Gagal menginisiasi pembayaran. Silakan coba lagi.');
        }

        $booking->update([
            'snap_token'       => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return $snapToken;
    }

    /**
     * Memverifikasi signature key dari payload webhook Midtrans.
     */
    public function verifySignature(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $signatureKey
    ): bool {
        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
        );

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Memproses payload notifikasi webhook dari Midtrans.
     *
     * Memvalidasi payload, memverifikasi signature, mencari booking,
     * dan mengupdate status booking berdasarkan transaction_status.
     *
     * @param  array<string, mixed> $payload
     * @throws \InvalidArgumentException jika payload tidak lengkap.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException jika signature tidak valid (403).
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException jika booking tidak ditemukan.
     */
    public function handleWebhookPayload(array $payload): void
    {
        // 1. Validasi kelengkapan payload
        $orderId       = $payload['order_id']      ?? null;
        $statusCode    = $payload['status_code']   ?? null;
        $grossAmount   = $payload['gross_amount']  ?? null;
        $signatureKey  = $payload['signature_key'] ?? null;

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            Log::warning('Midtrans webhook: payload tidak lengkap.', ['payload' => $payload]);
            throw new \InvalidArgumentException('Payload tidak lengkap.');
        }

        // 2. Verifikasi signature
        if (! $this->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans webhook: signature tidak valid.', ['order_id' => $orderId]);
            abort(403, 'Signature tidak valid.');
        }

        // 3. Cari booking
        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        if (! $booking) {
            Log::warning('Midtrans webhook: booking tidak ditemukan.', ['order_id' => $orderId]);
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Booking dengan order ID [{$orderId}] tidak ditemukan.");
        }

        // 4. Idempotency check — cegah double-processing
        if ($booking->status === 'paid') {
            Log::info('Midtrans webhook: notifikasi duplikat, diabaikan.', [
                'order_id'   => $orderId,
                'booking_id' => $booking->id,
            ]);
            return;
        }

        // 5. Update status booking berdasarkan transaction_status
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status']       ?? null;
        $paymentType       = $payload['payment_type']       ?? null;

        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $this->markAsPaid($booking, $paymentType);
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($booking, $paymentType);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $booking->update(['status' => 'expired']);
        }

        Log::info('Midtrans webhook diproses.', [
            'order_id'          => $orderId,
            'transaction_status' => $transactionStatus,
            'booking_id'        => $booking->id,
            'new_status'        => $booking->fresh()->status,
        ]);
    }

    /**
     * Menandai booking sebagai sudah dibayar (paid).
     */
    private function markAsPaid(Booking $booking, ?string $paymentType): void
    {
        $booking->update([
            'status'       => 'paid',
            'payment_type' => $paymentType,
            'paid_at'      => now(),
        ]);
    }
}
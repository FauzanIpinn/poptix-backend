<?php

namespace App\Services;

use App\Exceptions\BookingException;
use App\Exceptions\PaymentException;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected MidtransService $midtransService,
        protected BookingService $bookingService,
    ) {}

    public function initiateCheckout(Booking $booking): array {
        if ($booking->status === 'paid') {
            throw BookingException::alreadyPaid();
        }

        if ($booking->status !== 'pending') {
            throw BookingException::notPending();
        }

        if ($booking->expires_at && $booking->expires_at->isPast()) {
            throw BookingException::expired();
        }

        $snapToken = $this->midtransService->createSnapToken($booking);

        return [
            'snap_token' => $snapToken,
            'client_key' => config('midtrans.client_key'),
        ];
    }

    public function handleNotification(array $payload): Booking {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            Log::warning('Midtrans notification: payload tidak lengkap.', $payload);
            throw PaymentException::incompletePayload();
        }

        if (! $this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans notification: signature tidak valid.', ['order_id' => $orderId]);
            throw PaymentException::invalidSignature();
        }

        return DB::transaction(function () use ($payload, $orderId, $grossAmount) {
            $booking = Booking::where('midtrans_order_id', $orderId)->lockForUpdate()->first();

            if (! $booking) {
                Log::warning('Midtrans notification: booking tidak ditemukan.', ['order_id' => $orderId]);
                throw PaymentException::bookingNotFound();
            }

            if ($booking->status === 'paid') {
                Log::info('Midtrans notification: duplikat, diabaikan.', [
                    'order_id' => $orderId,
                    'booking_id' => $booking->id,
                ]);
                return $booking;
            }

            if (abs(((float) $grossAmount) - (float) $booking->total_price) > 0.01) {
                Log::critical('Midtrans notification: nominal tidak cocok, kemungkinan payload dimanipulasi.', [
                    'order_id' => $orderId,
                    'expected' => (float) $booking->total_price,
                    'received' => (float) $grossAmount,
                ]);
                throw PaymentException::amountMismatch();
            }

            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;
            $paymentType = $payload['payment_type'] ?? null;

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markAsPaid($booking, $paymentType);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markAsPaid($booking, $paymentType);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
                $booking->update(['status' => 'expired']);
                $this->bookingService->forgetBookedSeatsCache($booking->schedule_id);
            }

            Log::info('Midtrans notification diproses.', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'booking_id' => $booking->id,
            ]);

            return $booking->fresh();
        });
    }

    protected function markAsPaid(Booking $booking, ?string $paymentType): void {
        $booking->update([
            'status' => 'paid',
            'payment_type' => $paymentType,
            'paid_at' => now(),
        ]);

        $this->bookingService->forgetBookedSeatsCache($booking->schedule_id);
    }
}
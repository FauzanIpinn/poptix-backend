<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request): JsonResponse {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            Log::warning('Midtrans notification: payload tidak lengkap.', $payload);
            return response()->json(['message' => 'Payload tidak lengkap.'], 400);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
        );

        if (! hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans notification: signature tidak valid.', ['order_id' => $orderId]);
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        if (! $booking) {
            Log::warning('Midtrans notification: booking tidak ditemukan.', ['order_id' => $orderId]);
            return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
        }

        if ($booking->status === 'paid') {
            return response()->json(['message' => 'Notifikasi sudah pernah diproses sebelumnya.']);
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
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $booking->update(['status' => 'expired']);
        }

        Log::info('Midtrans notification diproses.', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'booking_id' => $booking->id,
        ]);

        return response()->json(['message' => 'Notifikasi berhasil diproses.']);
    }

    protected function markAsPaid(Booking $booking, ?string $paymentType): void {
        $booking->update([
            'status' => 'paid',
            'payment_type' => $paymentType,
            'paid_at' => now(),
        ]);
    }
}
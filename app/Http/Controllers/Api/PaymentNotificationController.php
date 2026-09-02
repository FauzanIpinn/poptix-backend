<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PaymentException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Traits\ApiResponse;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentNotificationController extends Controller
{
    use ApiResponse;

    public function __construct(protected PaymentService $paymentService) {}

    public function handle(Request $request): JsonResponse {
        try {
            $booking = $this->paymentService->handleNotification($request->all());
        } catch (PaymentException $e) {
            return $this->error($e->getMessage(), $e->statusCode());
        } catch (Throwable $e) {
            Log::error('Midtrans notification: kesalahan tak terduga.', [
                'exception' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return $this->error('Terjadi kesalahan saat memproses notifikasi.', 500);
        }

        return $this->success('Notifikasi berhasil diproses.', new BookingResource($booking));
    }
}
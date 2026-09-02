<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BookingException;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct(protected PaymentService $paymentService) {}

    public function checkout(Booking $booking): JsonResponse {
        $this->authorize('pay', $booking);

        try {
            $result = $this->paymentService->initiateCheckout($booking);
        } catch (BookingException $e) {
            return $this->error($e->getMessage(), $e->statusCode());
        }

        return $this->success('Checkout berhasil diinisiasi.', $result);
    }
}
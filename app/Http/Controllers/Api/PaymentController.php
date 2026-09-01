<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BookingException;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function checkout(Booking $booking): JsonResponse {
        $this->authorize('pay', $booking);

        try {
            $result = $this->paymentService->initiateCheckout($booking);
        } catch (BookingException $e) {
            return response()->json(['message' => $e->getMessage()], $e->statusCode());
        }

        return response()->json($result);
    }
}
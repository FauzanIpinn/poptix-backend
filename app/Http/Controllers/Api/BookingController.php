<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BookingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\SeatResource;
use App\Models\Booking;
use App\Models\Schedule;
use App\Http\Traits\ApiResponse;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    use ApiResponse;

    public function __construct(protected BookingService $bookingService) {}

    public function availableSeats(Schedule $schedule): AnonymousResourceCollection {
        return SeatResource::collection($this->bookingService->getAvailableSeats($schedule));
    }

    public function store(StoreBookingRequest $request): JsonResponse {
        $validated = $request->validated();

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $validated['schedule_id'],
                $validated['seat_ids'],
                $validated['idempotency_key'] ?? null,
            );
        } catch (BookingException $e) {
            return $this->error($e->getMessage(), $e->statusCode());
        }

        $booking->load(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat']);

        return $this->success('Booking berhasil dibuat.', new BookingResource($booking), 201);
    }

    public function index(): AnonymousResourceCollection {
        $bookings = auth()->user()
            ->bookings()
            ->with(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat'])
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Booking $booking): JsonResponse {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.studio.cinema', 'bookingSeats.seat']);

        return $this->success('Detail booking berhasil diambil.', new BookingResource($booking));
    }

    public function cancel(Booking $booking): JsonResponse {
        $this->authorize('cancel', $booking);
        $booking = $this->bookingService->cancelBooking($booking);

        return $this->success('Booking berhasil dibatalkan.', new BookingResource($booking));
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\SeatResource;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BookingController extends Controller
{
    public function availableSeats(Schedule $schedule): AnonymousResourceCollection {
        $schedule->load('cinema.seats');

        $bookedSeatIds = BookingSeat::where('schedule_id', $schedule->id)
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['pending', 'paid']);
            })
            ->pluck('seat_id')
            ->toArray();

        $seats = $schedule->cinema->seats->map(function (Seat $seat) use ($bookedSeatIds) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
            return $seat;
        });

        return SeatResource::collection($seats);
    }

    public function __construct(protected BookingService $bookingService) {}

    public function store(StoreBookingRequest $request): JsonResponse {
        $validated = $request->validated();

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $validated['schedule_id'],
                $validated['seat_ids'],
                $validated['idempotency_key'] ?? null,
            );
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return response()->json([
            'message' => 'Booking berhasil dibuat.',
            'data' => new BookingResource($booking),
        ], 201);
    }

    public function index(): AnonymousResourceCollection {
        $bookings = auth()->user()
            ->bookings()
            ->with(['schedule.movie', 'schedule.cinema'])
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Booking $booking): BookingResource|JsonResponse {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return new BookingResource($booking);
    }

    public function cancel(Booking $booking): JsonResponse {
        $this->authorize('cancel', $booking);
        $booking = $this->bookingService->cancelBooking($booking);

        return response()->json([
            'message' => 'Booking berhasil dibatalkan.',
            'data' => new BookingResource($booking),
        ]);
    }
}
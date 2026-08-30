<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\SeatResource;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    /**
     * Mengembalikan daftar kursi beserta status ketersediaannya untuk jadwal tertentu.
     *
     * Menggunakan satu query dengan subquery untuk menghindari N+1.
     */
    public function availableSeats(Schedule $schedule): AnonymousResourceCollection
    {
        // Eager load relasi yang dibutuhkan sekaligus
        $schedule->load('cinema.seats');

        // Ambil semua seat_id yang sudah dipesan dalam satu query
        $bookedSeatIds = BookingSeat::where('schedule_id', $schedule->id)
            ->whereHas('booking', fn ($q) => $q->whereIn('status', ['pending', 'paid']))
            ->pluck('seat_id')
            ->all();

        // Tambahkan attribute `is_booked` ke tiap seat tanpa query tambahan
        $seats = $schedule->cinema->seats->map(function ($seat) use ($bookedSeatIds) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
            return $seat;
        });

        return SeatResource::collection($seats);
    }

    /**
     * Membuat booking baru untuk pengguna yang sedang login.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
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
            'data'    => new BookingResource($booking),
        ], 201);
    }

    /**
     * Mengembalikan daftar semua booking milik pengguna yang sedang login.
     */
    public function index(): AnonymousResourceCollection
    {
        $bookings = auth()->user()
            ->bookings()
            ->with(['schedule.movie', 'schedule.cinema'])
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    /**
     * Mengembalikan detail sebuah booking (hanya pemilik yang bisa akses).
     */
    public function show(Booking $booking): BookingResource|JsonResponse
    {
        $this->authorize('view', $booking);

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return new BookingResource($booking);
    }

    /**
     * Membatalkan sebuah booking yang masih berstatus pending.
     */
    public function cancel(Booking $booking): JsonResponse
    {
        $this->authorize('cancel', $booking);

        $booking = $this->bookingService->cancelBooking($booking);

        return response()->json([
            'message' => 'Booking berhasil dibatalkan.',
            'data'    => new BookingResource($booking),
        ]);
    }
}
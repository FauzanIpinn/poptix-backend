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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function availableSeats(Schedule $schedule): AnonymousResourceCollection
    {
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

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $booking = DB::transaction(function () use ($validated, $request) {
                $schedule = Schedule::findOrFail($validated['schedule_id']);

                $seats = Seat::whereIn('id', $validated['seat_ids'])
                    ->lockForUpdate()
                    ->get();

                $alreadyBooked = BookingSeat::where('schedule_id', $schedule->id)
                    ->whereIn('seat_id', $validated['seat_ids'])
                    ->whereHas('booking', function ($query) {
                        $query->whereIn('status', ['pending', 'paid']);
                    })
                    ->exists();

                if ($alreadyBooked) {
                    throw new \Exception('Salah satu kursi yang kamu pilih baru saja dipesan orang lain.');
                }

                $totalPrice = $seats->count() * $schedule->price;

                $booking = Booking::create([
                    'user_id' => $request->user()->id,
                    'schedule_id' => $schedule->id,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(15),
                ]);

                foreach ($seats as $seat) {
                    $booking->bookingSeats()->create([
                        'schedule_id' => $schedule->id,
                        'seat_id' => $seat->id,
                        'price' => $schedule->price,
                    ]);
                }

                return $booking;
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409); // 409 Conflict — status code yang tepat buat resource conflict
        }

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return response()->json([
            'message' => 'Booking berhasil dibuat.',
            'data' => new BookingResource($booking),
        ], 201);
    }

    public function index(): AnonymousResourceCollection
    {
        $bookings = auth()->user()
            ->bookings()
            ->with(['schedule.movie', 'schedule.cinema'])
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Booking $booking): BookingResource|JsonResponse
    {
        $this->authorize('view', $booking); // reuse BookingPolicy dari Tahap 6

        $booking->load(['schedule.movie', 'schedule.cinema', 'bookingSeats.seat']);

        return new BookingResource($booking);
    }

    public function cancel(Booking $booking): JsonResponse
    {
        $this->authorize('cancel', $booking); // reuse BookingPolicy dari Tahap 6

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Booking berhasil dibatalkan.',
            'data' => new BookingResource($booking->fresh()),
        ]);
    }
}
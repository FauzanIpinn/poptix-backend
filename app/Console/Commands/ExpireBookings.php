<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Mengubah status booking pending yang sudah lewat batas waktu menjadi expired';

    public function handle(BookingService $bookingService): void
    {
        $expiredBookings = Booking::pending()
            ->where('expires_at', '<', now())
            ->get(['id', 'schedule_id']);

        if ($expiredBookings->isEmpty()) {
            $this->info("0 booking telah di-expire.");
            return;
        }

        $scheduleIds = $expiredBookings->pluck('schedule_id')->unique();

        $expiredCount = Booking::whereIn('id', $expiredBookings->pluck('id'))->update(['status' => 'expired']);

        foreach ($scheduleIds as $scheduleId) {
            try {
                $bookingService->forgetBookedSeatsCache($scheduleId);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal clear cache seat saat expire booking', [
                    'schedule_id' => $scheduleId,
                    'error'       => $e->getMessage()
                ]);
            }
        }

        $this->info("{$expiredCount} booking telah di-expire.");
    }
}
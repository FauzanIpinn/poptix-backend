<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Mengubah status booking pending yang sudah lewat batas waktu menjadi expired';

    public function handle(): void
    {
        $expiredCount = Booking::pending()
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("{$expiredCount} booking telah di-expire.");
    }
}
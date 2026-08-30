<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Schedule $schedule): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        if (! $user->hasRole('admin')) {
            return false;
        }

        // Cegah penghapusan jadwal yang masih memiliki booking aktif (pending/paid)
        // untuk menjaga integritas data dan melindungi transaksi pengguna.
        $hasActiveBookings = $schedule->bookings()
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        return ! $hasActiveBookings;
    }
}
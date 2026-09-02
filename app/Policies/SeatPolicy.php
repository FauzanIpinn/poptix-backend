<?php

namespace App\Policies;

use App\Models\Seat;
use App\Models\User;

class SeatPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Seat $seat): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Seat $seat): bool
    {
        if (! $user->hasRole('admin')) {
            return false;
        }

        // Cegah penghapusan kursi yang masih terpakai di booking aktif (pending/paid).
        $hasActiveBooking = $seat->bookingSeats()
            ->whereHas('booking', fn ($q) => $q->active())
            ->exists();

        return ! $hasActiveBooking;
    }
}
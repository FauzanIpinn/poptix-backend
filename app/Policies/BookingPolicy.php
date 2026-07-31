<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('user');
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('user');
    }

    public function cancel(User $user, Booking $booking): bool
    {
        // Cuma boleh cancel kalau:
        // 1. Booking ini emang miliknya sendiri
        // 2. Status booking-nya masih 'pending' (belum dibayar)
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }
}
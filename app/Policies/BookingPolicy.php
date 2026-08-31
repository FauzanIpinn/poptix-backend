<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool {
        return $user->hasRole('user');
    }

    public function view(User $user, Booking $booking): bool {
        return $user->id === $booking->user_id || $user->hasRole('admin');
    }

    public function create(User $user): bool {
        return $user->hasRole('user');
    }

    public function update(User $user, Booking $booking): bool {
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }

    public function cancel(User $user, Booking $booking): bool {
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }

    public function pay(User $user, Booking $booking): bool {
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }
}
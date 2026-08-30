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
        // User hanya bisa melihat booking miliknya sendiri.
        // Admin bisa melihat semua booking.
        return $user->id === $booking->user_id || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('user');
    }

    public function update(User $user, Booking $booking): bool
    {
        // Update booking hanya boleh dilakukan oleh pemiliknya
        // dan hanya saat status masih pending.
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }

    public function cancel(User $user, Booking $booking): bool
    {
        // Cancel hanya boleh jika:
        // 1. Booking ini memang miliknya sendiri
        // 2. Status booking-nya masih 'pending' (belum dibayar)
        return $user->id === $booking->user_id
            && $booking->status === 'pending';
    }
}
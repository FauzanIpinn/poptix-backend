<?php

namespace App\Policies;

use App\Models\Cinema;
use App\Models\User;

class CinemaPolicy
{
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Cinema $cinema): bool {
        return true;
    }

    public function create(User $user): bool {
        return $user->hasRole('admin');
    }

    public function update(User $user, Cinema $cinema): bool {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Cinema $cinema): bool {
        if (! $user->hasRole('admin')) {
            return false;
        }

        $hasActiveBookings = $cinema->schedules()
            ->whereHas('bookings', fn ($q) => $q->active())
            ->exists();

        return ! $hasActiveBookings;
    }
}
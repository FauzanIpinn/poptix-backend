<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Movie $movie): bool {
        return true;
    }

    public function create(User $user): bool {
        return $user->hasRole('admin');
    }

    public function update(User $user, Movie $movie): bool {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Movie $movie): bool {
        if (! $user->hasRole('admin')) {
            return false;
        }

        $hasActiveBookings = $movie->schedules()
            ->whereHas('bookings', fn ($q) => $q->active())
            ->exists();

        return ! $hasActiveBookings;
    }
}
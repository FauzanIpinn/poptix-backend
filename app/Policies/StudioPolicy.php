<?php

namespace App\Policies;

use App\Models\Studio;
use App\Models\User;

class StudioPolicy
{
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Studio $studio): bool {
        return true;
    }

    public function create(User $user): bool {
        return $user->hasRole('admin');
    }

    public function update(User $user, Studio $studio): bool {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Studio $studio): bool {
        if (! $user->hasRole('admin')) {
            return false;
        }

        $hasActiveSchedules = $studio->schedules()
            ->whereHas('bookings', fn ($q) => $q->active())
            ->exists();

        return ! $hasActiveSchedules;
    }
}
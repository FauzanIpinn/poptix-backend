<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Schedule $schedule): bool {
        return true;
    }

    public function create(User $user): bool {
        return $user->hasRole('admin');
    }

    public function update(User $user, Schedule $schedule): Response {
        if (! $user->hasRole('admin')) {
            return Response::deny('Kamu tidak punya izin untuk mengubah jadwal.');
        }

        if ($this->hasActiveBookings($schedule)) {
            return Response::deny('Jadwal ini masih punya booking aktif (pending/paid), tidak bisa diubah. Batalkan/selesaikan booking terkait terlebih dahulu.');
        }

        return Response::allow();
    }

    public function delete(User $user, Schedule $schedule): Response {
        if (! $user->hasRole('admin')) {
            return Response::deny('Kamu tidak punya izin untuk menghapus jadwal.');
        }

        if ($this->hasActiveBookings($schedule)) {
            return Response::deny('Jadwal ini masih punya booking aktif (pending/paid), tidak bisa dihapus.');
        }

        return Response::allow();
    }

    private function hasActiveBookings(Schedule $schedule): bool {
        return $schedule->bookings()
            ->whereIn('status', ['pending', 'paid'])
            ->exists();
    }
}
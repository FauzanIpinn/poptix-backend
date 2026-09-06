<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $hasActiveBookings = $user->bookings()->whereIn('status', ['pending', 'paid'])->exists();
        if ($hasActiveBookings) {
            return Redirect::back()->withErrors([
                'password' => 'Tidak bisa menghapus akun karena kamu masih memiliki booking aktif (Pending/Paid).',
            ], 'userDeletion');
        }

        try {
            $user->delete();
            Auth::logout();
        } catch (\Exception $e) {
            return Redirect::back()->withErrors([
                'password' => 'Terjadi kesalahan sistem saat menghapus akun.',
            ], 'userDeletion');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

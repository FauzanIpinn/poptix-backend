<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View {
        $schedules = Schedule::with(['movie', 'cinema'])->latest()->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.schedules.create', compact('movies', 'cinemas'));
    }

    public function store(StoreScheduleRequest $request): RedirectResponse {
        Schedule::create($request->validated());

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View {
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.schedules.edit', compact('schedule', 'movies', 'cinemas'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse {
        $schedule->update($request->validated());

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}

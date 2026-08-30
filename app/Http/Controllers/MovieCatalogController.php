<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'now_showing');

        // Gunakan scope yang sudah didefinisikan di model
        $movies = Movie::when(
            $status === 'now_showing',
            fn ($q) => $q->nowShowing(),
            fn ($q) => $q->comingSoon()
        )
        ->with(['schedules.cinema'])   // eager load untuk mencegah N+1
        ->latest()
        ->get();

        return view('user.catalog', [
            'movies' => $movies,
            'status' => $status,
        ]);
    }

    public function show(Movie $movie): View
    {
        // Eager load jadwal beserta cinema-nya, filter jadwal yang masih akan datang
        $schedules = $movie->schedules()
            ->with('cinema')
            ->where('show_date', '>=', now()->format('Y-m-d'))
            ->orderBy('show_date')
            ->orderBy('show_time')
            ->get()
            ->groupBy(fn ($schedule) => $schedule->show_date->format('Y-m-d'));

        return view('movies.show', compact('movie', 'schedules'));
    }
}

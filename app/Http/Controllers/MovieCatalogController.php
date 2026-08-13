<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieCatalogController extends Controller
{
    public function indexx(Request $request): View {
        $status = $request->get('status', 'now_showing');
        $movie = Movie::where('status', $status)
            ->with('cinemas:id,name')
            ->get();

        return view('user.catalog', [
            'movies' => $movie,
            'status' => $status,
        ]);
    }

    public function show(Movie $movie): View {
        $schedule = $movie->schedules()
            ->with('cinema')
            ->where('show_date', '>=', now()->format('Y-m-d'))
            ->orderBy('show_date')
            ->orderBy('show_time')
            ->get()
            ->groupBy(fn ($schedule) => $schedule->show_date->format('Y-m-d'));
        
        return view('movies.show', compact('movie', 'schedules'));
    }
}

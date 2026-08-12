<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View {
        $movies = Movie::latest()->paginate(10);
        return view('admin.movies.index', compact('movies'));
    }

    public function create(): View {
        return view('admin.movies.create');
    }

    public function store(StoreMovieRequest $request): RedirectResponse {
        $validated = $request->validated();
        if ($request->hasFile('poster')) {
            $result = cloudinary()->uploadApi()->upload($request->file('poster')->getRealPath(), ['folder' => 'poptix/posters']);
            $validated['poster'] = $result['secure_url'];
        }

        Movie::create($validated);
        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film berhasil ditambahkan.');
    }

    public function update(UpdateMovieRequest $request, Movie $movie): RedirectResponse {
        $validated = $request->validated();
        if ($request->hasFile('poster')) {
            $result = cloudinary()->uploadApi()->upload($request->file('poster')->getRealPath(), ['folder' => 'poptix/posters']);
            $validated['poster'] = $result['secure_url'];
        }

        $movie->update($validated);
        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film berhasil diperbarui.');
    }

    public function destroy(Movie $movie): RedirectResponse {
    $this->authorize('delete', $movie);
    
        $movie->delete();
        
        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film berhasil dihapus.');
    }
}

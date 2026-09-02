<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\UploadsPoster;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    use ApiResponse, UploadsPoster;

    public function index(): AnonymousResourceCollection
    {
        return MovieResource::collection(Movie::latest()->paginate(10));
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('poster')) {
            $validated['poster'] = $this->uploadPoster($request->file('poster'));
        }

        $movie = Movie::create($validated);

        return $this->success('Film berhasil ditambahkan.', new MovieResource($movie), 201);
    }

    public function show(Movie $movie): JsonResponse
    {
        return $this->success('Detail film berhasil diambil.', new MovieResource($movie));
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('poster')) {
            $validated['poster'] = $this->uploadPoster($request->file('poster'));
        }

        $movie->update($validated);

        return $this->success('Film berhasil diperbarui.', new MovieResource($movie));
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $this->authorize('delete', $movie);

        $movie->delete();

        return $this->success('Film berhasil dihapus.');
    }
}
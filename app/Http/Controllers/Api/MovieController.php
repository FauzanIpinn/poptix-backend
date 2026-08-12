<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Movie::query();

        if ($request->has('status') && in_array($request->status, ['now_showing', 'coming_soon'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $movies = $query->latest()->paginate(10);

        return MovieResource::collection($movies);
    }

    public function show(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }
}
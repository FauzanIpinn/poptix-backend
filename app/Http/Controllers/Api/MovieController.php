<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Http\Traits\ApiResponse;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    use ApiResponse;

    public function index(Request $request): AnonymousResourceCollection {
        $query = Movie::query();

        if ($request->filled('status') && in_array($request->status, ['now_showing', 'coming_soon'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $movies = $query->latest()->paginate(10);

        return MovieResource::collection($movies);
    }

    public function show(Movie $movie): JsonResponse {
        return $this->success('Detail film berhasil diambil.', new MovieResource($movie));
    }
}
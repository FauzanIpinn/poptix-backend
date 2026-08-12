<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CinemaResource;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CinemaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Cinema::query();

        if ($request->has('brand')) {
            $query->brand($request->brand); // scope yang udah dibuat di Tahap 3
        }

        if ($request->has('city')) {
            $query->city($request->city); // scope yang udah dibuat di Tahap 3
        }

        $cinemas = $query->orderBy('name')->paginate(10);

        return CinemaResource::collection($cinemas);
    }

    public function show(Cinema $cinema): CinemaResource
    {
        return new CinemaResource($cinema);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CinemaResource;
use App\Http\Traits\ApiResponse;
use App\Models\Cinema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CinemaController extends Controller
{
    use ApiResponse;

    public function index(Request $request): AnonymousResourceCollection {
        $query = Cinema::query();

        if ($request->filled('brand')) {
            $query->brand($request->brand);
        }

        if ($request->filled('city')) {
            $query->city($request->city);
        }

        $cinemas = $query->orderBy('name')->paginate(10);

        return CinemaResource::collection($cinemas);
    }

    public function show(Cinema $cinema): JsonResponse {
        return $this->success('Detail bioskop berhasil diambil.', new CinemaResource($cinema->load('studios')));
    }
}
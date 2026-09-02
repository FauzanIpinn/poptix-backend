<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCinemaRequest;
use App\Http\Requests\UpdateCinemaRequest;
use App\Http\Resources\CinemaResource;
use App\Http\Traits\ApiResponse;
use App\Models\Cinema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CinemaController extends Controller
{
    use ApiResponse;

    public function index(): AnonymousResourceCollection
    {
        return CinemaResource::collection(Cinema::latest()->paginate(10));
    }

    public function store(StoreCinemaRequest $request): JsonResponse
    {
        $cinema = Cinema::create($request->validated());

        return $this->success('Bioskop berhasil ditambahkan.', new CinemaResource($cinema), 201);
    }

    public function show(Cinema $cinema): JsonResponse
    {
        $cinema->load('studios');

        return $this->success('Detail bioskop berhasil diambil.', new CinemaResource($cinema));
    }

    public function update(UpdateCinemaRequest $request, Cinema $cinema): JsonResponse
    {
        $cinema->update($request->validated());

        return $this->success('Bioskop berhasil diperbarui.', new CinemaResource($cinema));
    }

    public function destroy(Cinema $cinema): JsonResponse
    {
        $this->authorize('delete', $cinema);

        $cinema->delete();

        return $this->success('Bioskop berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudioRequest;
use App\Http\Requests\UpdateStudioRequest;
use App\Http\Resources\StudioResource;
use App\Http\Traits\ApiResponse;
use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class StudioController extends Controller
{
    use ApiResponse;

    public function index(): AnonymousResourceCollection
    {
        return StudioResource::collection(Studio::with('cinema')->latest()->paginate(10));
    }

    public function store(StoreStudioRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $rows = $validated['rows'] ?? ['A', 'B', 'C', 'D', 'E'];
        $seatsPerRow = $validated['seats_per_row'] ?? 10;

        $studio = DB::transaction(function () use ($validated, $rows, $seatsPerRow) {
            $studio = Studio::create([
                'cinema_id' => $validated['cinema_id'],
                'name'      => $validated['name'],
            ]);

            $seatsToInsert = [];
            foreach ($rows as $row) {
                for ($number = 1; $number <= $seatsPerRow; $number++) {
                    $seatsToInsert[] = [
                        'cinema_id'  => $studio->cinema_id,
                        'studio_id'  => $studio->id,
                        'row'        => $row,
                        'number'     => $number,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            Seat::insert($seatsToInsert);

            return $studio;
        });

        return $this->success('Studio berhasil ditambahkan.', new StudioResource($studio->load('cinema')), 201);
    }

    public function show(Studio $studio): JsonResponse
    {
        $studio->load(['cinema', 'seats']);

        return $this->success('Detail studio berhasil diambil.', new StudioResource($studio));
    }

    public function update(UpdateStudioRequest $request, Studio $studio): JsonResponse
    {
        $studio->update($request->validated());

        return $this->success('Studio berhasil diperbarui.', new StudioResource($studio->load('cinema')));
    }

    public function destroy(Studio $studio): JsonResponse
    {
        $this->authorize('delete', $studio);

        $studio->delete();

        return $this->success('Studio berhasil dihapus.');
    }
}
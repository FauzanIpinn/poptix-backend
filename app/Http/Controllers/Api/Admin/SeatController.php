<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateSeatsRequest;
use App\Http\Resources\SeatResource;
use App\Http\Traits\ApiResponse;
use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    use ApiResponse;

    public function index(Studio $studio): JsonResponse
    {
        $seats = $studio->seats()->orderBy('row')->orderBy('number')->get();

        return $this->success('Daftar kursi berhasil diambil.', SeatResource::collection($seats)->resolve());
    }

    /**
     * Generate kursi baru untuk sebuah studio (menambah baris kursi tanpa mengganggu kursi lama).
     */
    public function generate(GenerateSeatsRequest $request, Studio $studio): JsonResponse
    {
        $validated = $request->validated();

        $existing = $studio->seats()
            ->whereIn('row', $validated['rows'])
            ->pluck('row')
            ->unique();

        if ($existing->isNotEmpty()) {
            return $this->error('Baris kursi berikut sudah ada: ' . $existing->implode(', '), 422);
        }

        $seatsToInsert = [];
        foreach ($validated['rows'] as $row) {
            for ($number = 1; $number <= $validated['seats_per_row']; $number++) {
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

        $seats = $studio->seats()->orderBy('row')->orderBy('number')->get();

        return $this->success('Kursi berhasil ditambahkan.', SeatResource::collection($seats)->resolve(), 201);
    }

    public function destroy(Seat $seat): JsonResponse
    {
        $this->authorize('delete', $seat);

        $seat->delete();

        return $this->success('Kursi berhasil dihapus.');
    }
}
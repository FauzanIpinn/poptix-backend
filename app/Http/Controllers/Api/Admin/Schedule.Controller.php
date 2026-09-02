<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Http\Traits\ApiResponse;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function index(): AnonymousResourceCollection
    {
        $schedules = Schedule::with(['movie', 'cinema'])->latest()->paginate(10);

        return ScheduleResource::collection($schedules);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = Schedule::create($request->validated());
        $schedule->load(['movie', 'cinema']);

        return $this->success('Jadwal berhasil ditambahkan.', new ScheduleResource($schedule), 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['movie', 'studio.cinema']);

        return $this->success('Detail jadwal berhasil diambil.', new ScheduleResource($schedule));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): JsonResponse
    {
        $schedule->update($request->validated());
        $schedule->load(['movie', 'cinema']);

        return $this->success('Jadwal berhasil diperbarui.', new ScheduleResource($schedule));
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return $this->success('Jadwal berhasil dihapus.');
    }
}
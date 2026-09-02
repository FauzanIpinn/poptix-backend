<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Http\Traits\ApiResponse;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function index(Request $request): AnonymousResourceCollection {
        $query = Schedule::with(['movie', 'studio.cinema']);

        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->filled('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('show_date', $request->date);
        }

        $schedules = $query->orderBy('show_date')->orderBy('show_time')->paginate(15);

        return ScheduleResource::collection($schedules);
    }

    public function show(Schedule $schedule): JsonResponse {
        $schedule->load(['movie', 'studio.cinema']);

        return $this->success('Detail jadwal berhasil diambil.', new ScheduleResource($schedule));
    }
}
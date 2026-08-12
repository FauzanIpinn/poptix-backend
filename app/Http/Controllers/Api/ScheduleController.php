<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Schedule::with(['movie', 'cinema']);

        if ($request->has('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->has('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }

        if ($request->has('date')) {
            $query->whereDate('show_date', $request->date);
        }

        $schedules = $query->orderBy('show_date')->orderBy('show_time')->paginate(15);

        return ScheduleResource::collection($schedules);
    }

    public function show(Schedule $schedule): ScheduleResource
    {
        $schedule->load(['movie', 'cinema']);

        return new ScheduleResource($schedule);
    }
}
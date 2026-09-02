<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index() {
        $todayTickets = Booking::whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        $monthlyRevenue = Booking::whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->where('status', 'paid')
            ->sum('total_price');

        $activeMovies = Movie::count();

        $recentBookings = Booking::with(['user', 'schedule.movie'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayTickets', 
            'monthlyRevenue', 
            'activeMovies', 
            'recentBookings'
        ));
    }
}
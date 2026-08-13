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
        $todayTickets = Booking::whereDate('created_at', Carbon::today())
            ->where('payment_status', 'success')
            ->count();

        $monthlyRevenue = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'success')
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
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue'    => Booking::whereIn('status', ['confirmed','completed'])->sum('total_amount'),
            'bookings'   => Booking::count(),
            'properties' => Property::count(),
            'users'      => User::where('role','user')->count(),
            'pending'    => Booking::whereIn('status',['pending','waiting_payment','paid_unverified'])->count(),
        ];

        // Chart: revenue 6 bulan terakhir
        $revenueChart = Booking::whereIn('status', ['confirmed','completed'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Chart: booking per status
        $statusChart = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->get();

        // Chart: booking by category
        $categoryChart = Booking::join('properties','bookings.property_id','=','properties.id')
            ->join('categories','properties.category_id','=','categories.id')
            ->selectRaw('categories.name as cat, COUNT(bookings.id) as total')
            ->groupBy('categories.name')
            ->get();

        $recentBookings = Booking::with(['property','user'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'stats', 'revenueChart', 'statusChart', 'categoryChart', 'recentBookings'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $categories   = Category::withCount('properties')->get();
        $featured     = Property::active()->with('category')->orderByDesc('rating_avg')->take(6)->get();
        $testimonials = Testimonial::with(['user', 'property'])->where('status', 'approved')->latest()->take(6)->get();
        $stats = [
            'properties' => Property::active()->count(),
            'cities'     => Property::active()->distinct('city')->count('city'),
            'bookings'   => \App\Models\Booking::whereIn('status', ['confirmed','completed'])->count(),
        ];
        return view('home.index', compact('categories', 'featured', 'testimonials', 'stats'));
    }
}

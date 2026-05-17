<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::active()->with('category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('city', 'like', "%$q%")
                   ->orWhere('province', 'like', "%$q%")
                   ->orWhere('address', 'like', "%$q%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_night', 'asc'),
            'price_desc' => $query->orderBy('price_per_night', 'desc'),
            'rating'     => $query->orderByDesc('rating_avg'),
            default      => $query->orderByDesc('rating_count'),
        };

        $properties = $query->paginate(9)->withQueryString();
        $categories = Category::all();
        $cities     = Property::active()->distinct()->pluck('city')->sort();

        return view('explore.index', compact('properties', 'categories', 'cities'));
    }

    public function show(string $slug)
    {
        $property = Property::active()
            ->with(['category', 'images', 'testimonials.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Property::active()
            ->where('category_id', $property->category_id)
            ->where('id', '!=', $property->id)
            ->take(3)->get();

        return view('explore.show', compact('property', 'related'));
    }
}

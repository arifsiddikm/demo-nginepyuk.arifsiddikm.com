<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Property;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::with(['user','property'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $testimonials = $query->paginate(15)->withQueryString();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'approved']);

        // Update properti rating
        $property = $testimonial->property;
        $avg = Testimonial::where('property_id', $property->id)
            ->where('status','approved')->avg('rating');
        $count = Testimonial::where('property_id', $property->id)
            ->where('status','approved')->count();
        $property->update(['rating_avg' => round($avg, 1), 'rating_count' => $count]);

        return back()->with('success','Ulasan berhasil disetujui.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'rejected']);
        return back()->with('success','Ulasan ditolak.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success','Ulasan berhasil dihapus.');
    }
}

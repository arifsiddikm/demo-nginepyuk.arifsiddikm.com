<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [
            'total'     => Booking::where('user_id', $user->id)->count(),
            'active'    => Booking::where('user_id', $user->id)->whereIn('status', ['pending','waiting_payment','paid_unverified','confirmed'])->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];
        $recentBookings = Booking::with('property')->where('user_id', $user->id)->latest()->take(5)->get();
        return view('dashboard.index', compact('stats', 'recentBookings'));
    }

    public function bookings()
    {
        $bookings = Booking::with('property')
            ->where('user_id', Auth::id())
            ->latest()->paginate(10);
        return view('dashboard.bookings', compact('bookings'));
    }

    public function profile()
    {
        return view('dashboard.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        $user->update($request->only('name', 'phone', 'address'));
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah.');
    }

    public function submitTestimonial(Request $request, string $code)
    {
        $booking = Booking::with('property')
            ->where('booking_code', $code)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        if ($booking->testimonial) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        Testimonial::create([
            'user_id'     => Auth::id(),
            'property_id' => $booking->property_id,
            'booking_id'  => $booking->id,
            'rating'      => $request->rating,
            'review'      => $request->review,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! Menunggu persetujuan admin.');
    }
}

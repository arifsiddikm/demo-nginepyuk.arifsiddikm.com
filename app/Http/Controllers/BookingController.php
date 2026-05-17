<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BankAccount;
use App\Models\Property;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(protected MailService $mail) {}

    public function checkout(Request $request, string $slug)
    {
        $property = Property::active()->where('slug', $slug)->firstOrFail();

        $checkin  = $request->get('checkin', now()->addDay()->format('Y-m-d'));
        $checkout = $request->get('checkout', now()->addDays(2)->format('Y-m-d'));
        $rooms    = max(1, (int) $request->get('rooms', 1));

        // Validasi tanggal
        if ($checkin >= $checkout) {
            return back()->with('error', 'Tanggal checkout harus setelah check-in.');
        }

        $nights = (int) now()->parse($checkin)->diffInDays($checkout);
        if ($nights < 1) {
            return back()->with('error', 'Minimal menginap 1 malam.');
        }

        // Cek ketersediaan kamar real-time
        $available = $property->availableRooms($checkin, $checkout);
        if ($available < $rooms) {
            return back()->with('error', "Maaf, kamar yang tersedia hanya {$available} unit untuk tanggal tersebut.");
        }

        $subtotal   = $property->price_per_night * $nights * $rooms;
        $tax        = $subtotal * 0.11; // PPN 11%
        $total      = $subtotal + $tax;
        $banks      = BankAccount::where('is_active', true)->get();

        return view('booking.checkout', compact('property', 'checkin', 'checkout', 'nights', 'rooms', 'subtotal', 'tax', 'total', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id'     => 'required|exists:properties,id',
            'checkin_date'    => 'required|date|after_or_equal:today',
            'checkout_date'   => 'required|date|after:checkin_date',
            'rooms'           => 'required|integer|min:1',
            'guests'          => 'required|integer|min:1',
            'guest_name'      => 'required|string|max:100',
            'guest_email'     => 'required|email',
            'guest_phone'     => 'nullable|string|max:20',
            'special_request' => 'nullable|string|max:500',
            'payment_method'  => 'required|in:midtrans,bank_transfer',
        ]);

        $property = Property::findOrFail($request->property_id);

        // --- Anti Overbooking: DB Transaction + Lock ---
        $booking = DB::transaction(function () use ($request, $property) {
            // Re-check availability inside transaction
            $available = $property->availableRooms($request->checkin_date, $request->checkout_date);
            if ($available < $request->rooms) {
                throw new \Exception("Maaf, kamar tidak tersedia untuk tanggal yang dipilih. Sisa: {$available} kamar.");
            }

            $nights   = (int) now()->parse($request->checkin_date)->diffInDays($request->checkout_date);
            $subtotal = $property->price_per_night * $nights * $request->rooms;
            $tax      = $subtotal * 0.11;
            $total    = $subtotal + $tax;

            return Booking::create([
                'booking_code'    => Booking::generateCode(),
                'user_id'         => Auth::id(),
                'property_id'     => $property->id,
                'guest_name'      => $request->guest_name,
                'guest_email'     => $request->guest_email,
                'guest_phone'     => $request->guest_phone,
                'checkin_date'    => $request->checkin_date,
                'checkout_date'   => $request->checkout_date,
                'nights'          => $nights,
                'rooms'           => $request->rooms,
                'guests'          => $request->guests,
                'special_request' => $request->special_request,
                'price_per_night' => $property->price_per_night,
                'subtotal'        => $subtotal,
                'tax_amount'      => $tax,
                'total_amount'    => $total,
                'payment_method'  => $request->payment_method,
                'status'          => 'pending',
                'expired_at'      => now()->addMinutes(30),
            ]);
        });

        // Kirim notif email ke admin
        try {
            $this->mail->sendNewBookingToAdmin($booking->load('property'));
            $this->mail->sendOrderConfirmationToUser($booking);
        } catch (\Throwable $e) {
            \Log::error('Mail error on booking store: ' . $e->getMessage());
        }

        return redirect()->route('payment.show', $booking->booking_code)
            ->with('success', 'Pesanan berhasil dibuat! Selesaikan pembayaran Anda.');
    }

    public function show(string $code)
    {
        $booking = Booking::with('property')->where('booking_code', $code)->firstOrFail();

        // Auto expire
        if ($booking->isExpired()) {
            $booking->update(['status' => 'expired']);
        }

        $banks = BankAccount::where('is_active', true)->get();
        return view('booking.show', compact('booking', 'banks'));
    }

    public function uploadTransfer(Request $request, string $code)
    {
        $request->validate([
            'transfer_proof' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'transfer_proof.required' => 'Bukti transfer wajib diupload.',
            'transfer_proof.image'    => 'File harus berupa gambar.',
            'transfer_proof.max'      => 'Ukuran file maksimal 3MB.',
        ]);

        $booking = Booking::where('booking_code', $code)->firstOrFail();

        if (!in_array($booking->status, ['pending', 'waiting_payment'])) {
            return back()->with('error', 'Status pesanan tidak memungkinkan upload bukti transfer.');
        }

        $path = $request->file('transfer_proof')->store('transfer_proofs', 'public');

        $booking->update([
            'transfer_proof'      => $path,
            'transfer_uploaded_at'=> now(),
            'status'              => 'waiting_payment',
        ]);

        try {
            $this->mail->sendTransferProofToAdmin($booking->load('property'));
            $this->mail->sendTransferProofThanksToUser($booking);
        } catch (\Throwable $e) {
            \Log::error('Mail error on transfer proof: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti transfer berhasil diupload. Kami akan segera memverifikasi.');
    }

    // User dashboard bookings
    public function myBookings()
    {
        $bookings = Booking::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.bookings', compact('bookings'));
    }

    public function downloadTicket(string $code)
    {
        $query = Booking::with('property.category')->where('booking_code', $code);

        // Admin bisa download tiket siapapun, user hanya miliknya sendiri
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereNull('user_id'); // guest checkout
            });
        }

        $booking = $query->firstOrFail();

        if (!in_array($booking->status, ['confirmed', 'completed'])) {
            return back()->with('error', 'Tiket hanya tersedia untuk booking yang sudah dikonfirmasi.');
        }

        $pdf = app(\App\Services\PdfService::class);
        return $pdf->downloadTicket($booking);
    }
}

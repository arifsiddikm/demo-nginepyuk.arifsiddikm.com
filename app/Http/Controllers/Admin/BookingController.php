<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\MailService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function __construct(
        protected MailService $mail,
        protected PdfService $pdf
    ) {}

    public function index(Request $request)
    {
        $query = Booking::with(['property','user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('booking_code','like',"%$q%")
                   ->orWhere('guest_name','like',"%$q%")
                   ->orWhere('guest_email','like',"%$q%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $statuses = ['pending','waiting_payment','paid_unverified','confirmed','completed','expired','cancelled'];

        return view('admin.bookings.index', compact('bookings','statuses'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['property.category','user']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Admin konfirmasi pembayaran — allow semua status yang belum confirmed/completed
     */
    public function confirm(Booking $booking)
    {
        if (in_array($booking->status, ['confirmed','completed','cancelled'])) {
            return back()->with('error', 'Pesanan sudah ' . $booking->status_label . ', tidak perlu dikonfirmasi lagi.');
        }

        // Load relasi dulu sebelum update
        $booking->load('property.category');

        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->user()->name,
            'paid_at'      => $booking->paid_at ?? now(),
        ]);

        $mailError = null;
        try {
            // Generate tiket PDF
            $pdfPath = $this->pdf->generateTicket($booking);

            // Kirim tiket + konfirmasi ke pembeli saja
            $this->mail->sendBookingConfirmedWithTicket($booking, $pdfPath);

        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            Log::error('Confirm booking email/pdf error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
        }

        $msg = 'Pesanan berhasil dikonfirmasi & tiket dikirim ke ' . $booking->guest_email;
        if ($mailError) {
            $msg = 'Pesanan dikonfirmasi. (Gagal kirim email: ' . $mailError . ')';
        }

        return back()->with('success', $msg);
    }

    public function complete(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Pesanan harus berstatus "Dikonfirmasi" untuk diselesaikan.');
        }
        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan ditandai selesai.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['completed','cancelled'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }
        $booking->update([
            'status'      => 'cancelled',
            'admin_notes' => $request->reason,
        ]);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting_payment,paid_unverified,confirmed,completed,expired,cancelled'
        ]);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function uploadProof(Request $request, Booking $booking)
    {
        $request->validate([
            'transfer_proof' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'auto_confirm'   => 'required|in:0,1',
        ]);

        $path = $request->file('transfer_proof')->store('transfer_proofs', 'public');

        $booking->update([
            'transfer_proof'       => $path,
            'transfer_uploaded_at' => now(),
            'status'               => 'waiting_payment',
        ]);

        if ($request->auto_confirm == '1') {
            $booking->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->user()->name . ' (admin)',
                'paid_at'      => now(),
            ]);
            try {
                $pdfPath = $this->pdf->generateTicket($booking->load('property'));
                $this->mail->sendBookingConfirmedWithTicket($booking, $pdfPath);
            } catch (\Throwable $e) {
                Log::error('Admin uploadProof confirm ticket: ' . $e->getMessage());
            }
            return back()->with('success', 'Bukti diupload & pesanan dikonfirmasi. Tiket dikirim ke pembeli.');
        }

        try {
            $this->mail->sendTransferProofToAdmin($booking->load('property'));
        } catch (\Throwable $e) {
            Log::error('Admin uploadProof mail: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti transfer berhasil diupload oleh admin.');
    }

    public function exportPdf(Request $request)
    {
        $bookings = Booking::with(['property','user'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at','>=',$request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at','<=',$request->date_to))
            ->latest()->get();

        $pdf = Pdf::loadView('pdf.report_bookings', compact('bookings'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Laporan-Booking-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $bookings = Booking::with(['property','user'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()->get();

        $fileName = 'Laporan-Booking-' . now()->format('Ymd') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($bookings) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['Kode Booking','Nama','Email','Properti','Check-in','Check-out','Malam','Total','Metode','Status','Tgl Pesan']);
            foreach ($bookings as $b) {
                fputcsv($f, [
                    $b->booking_code,
                    $b->guest_name,
                    $b->guest_email,
                    $b->property->name ?? '-',
                    $b->checkin_date,
                    $b->checkout_date,
                    $b->nights,
                    $b->total_amount,
                    $b->payment_method,
                    $b->status,
                    $b->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}

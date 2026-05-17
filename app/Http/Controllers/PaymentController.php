<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MailService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected MailService $mail,
        protected PdfService $pdf
    ) {}

    public function show(string $code)
    {
        $booking = Booking::with('property')->where('booking_code', $code)->firstOrFail();
        if ($booking->isExpired()) {
            $booking->update(['status' => 'expired']);
        }
        $banks = \App\Models\BankAccount::where('is_active', true)->get();
        return view('payment.show', compact('booking', 'banks'));
    }

    /**
     * Ambil snap token dari Riplabs.
     * Jika order_id sudah pernah dikirim ke Midtrans, pakai suffix timestamp
     * supaya tidak dapat error "order_id has already been taken".
     */
    public function getSnapToken(Request $request)
    {
        $request->validate(['booking_code' => 'required|string']);

        $booking = Booking::with('property')->where('booking_code', $request->booking_code)->firstOrFail();

        if ($booking->isExpired()) {
            return response()->json(['status' => false, 'message' => 'Pesanan sudah kadaluarsa.']);
        }

        // Jika sudah ada snap token valid & midtrans_order_id sama, coba pakai lagi
        // Jika refresh/retry, buat order_id unik baru agar Midtrans tidak reject
        $midtransOrderId = $booking->midtrans_order_id;
        $isRetry = !empty($midtransOrderId);

        // Selalu buat order_id baru saat retry untuk hindari "already taken"
        $orderId = $isRetry
            ? $booking->booking_code . '-' . time()
            : $booking->booking_code;

        try {
            $payload = [
                'key'         => env('RIPLABS_KEY'),
                'order_id'    => $orderId,
                'total_harga' => (int) $booking->total_amount,
                'nama'        => $booking->guest_name,
                'email'       => $booking->guest_email,
                'notelp'      => $booking->guest_phone ?? '0',
                'namaproduk'  => 'Reservasi ' . $booking->property->name . ' (' . $booking->nights . ' malam)',
            ];

            Log::info('Riplabs payload: ' . json_encode($payload));

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept'  => 'application/json',
                    'Cookie'  => 'ci_session=' . env('RIPLABS_CI_SESSION', '66dcb99e80462b95dd17b2f24248fbda60398271'),
                ])
                ->asForm()
                ->post(env('RIPLABS_SNAPTOKEN_URL'), $payload);

            $rawBody = $response->body();
            $httpCode = $response->status();
            Log::info("Riplabs HTTP {$httpCode}: {$rawBody}");

            $data = $response->json();

            if (!empty($data['snaptoken'])) {
                $booking->update([
                    'midtrans_snap_token' => $data['snaptoken'],
                    'midtrans_order_id'   => $orderId,
                ]);
                return response()->json(['status' => true, 'snaptoken' => $data['snaptoken']]);
            }

            $msg = $data['message'] ?? ('Gagal [HTTP ' . $httpCode . ']: ' . $rawBody);
            return response()->json(['status' => false, 'message' => $msg]);

        } catch (\Throwable $e) {
            Log::error('Riplabs getSnapToken error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Callback dari Riplabs/Midtrans
     * URL: POST /payment/midtrans/notification
     */
    public function midtransNotification(Request $request)
    {
        $transactionStatus = $request->input('transaction_status');
        $paymentType       = $request->input('payment_type');
        $orderId           = $request->input('order_id');

        if (!$orderId) {
            return response('invalid', 400);
        }

        // order_id bisa berupa NGINEPYUK... atau NGINEPYUK...-timestamp (retry)
        $baseCode = preg_replace('/-\d+$/', '', $orderId);
        $booking  = Booking::with('property')->where('booking_code', $baseCode)->first();

        if (!$booking) {
            Log::warning("Midtrans callback: booking not found for order_id={$orderId}");
            return response('not_found', 404);
        }

        Log::info("Midtrans callback: order={$orderId} status={$transactionStatus} type={$paymentType}");

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if (!in_array($booking->status, ['confirmed', 'completed'])) {
                $booking->update([
                    'status'                => 'paid_unverified',
                    'midtrans_payment_type' => $paymentType,
                    'paid_at'               => now(),
                ]);
                try {
                    $this->mail->sendMidtransSuccessToAdmin($booking);
                } catch (\Throwable $e) {
                    Log::error('Mail error midtrans admin: ' . $e->getMessage());
                }
                $this->confirmBooking($booking);
            }
        } elseif ($transactionStatus === 'pending') {
            if (!in_array($booking->status, ['confirmed','completed'])) {
                $booking->update(['status' => 'waiting_payment']);
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            if (!in_array($booking->status, ['confirmed', 'completed'])) {
                $booking->update(['status' => 'cancelled']);
            }
        }

        return response('OK', 200);
    }

    public function midtransFinish(Request $request)
    {
        $orderId           = $request->get('order_id');
        $transactionStatus = $request->get('transaction_status');
        $statusCode        = $request->get('status_code');

        if (!$orderId) {
            return redirect('/')->with('error', 'Terjadi kesalahan pada proses pembayaran.');
        }

        // Strip timestamp suffix jika ada
        $baseCode = preg_replace('/-\d+$/', '', $orderId);
        $booking  = Booking::where('booking_code', $baseCode)->first();

        if (!$booking) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan.');
        }

        if (in_array($transactionStatus, ['capture', 'settlement']) && $statusCode == '200') {
            return redirect()->route('payment.show', $booking->booking_code)
                ->with('success', '🎉 Pembayaran berhasil! Booking Anda sedang diproses.');
        }

        return redirect()->route('payment.show', $booking->booking_code)
            ->with('info', 'Status pembayaran: ' . $transactionStatus . '. Kami akan memperbarui status pesanan Anda.');
    }

    private function confirmBooking(Booking $booking): void
    {
        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => 'system_midtrans',
        ]);

        try {
            $pdfPath = $this->pdf->generateTicket($booking);
            $this->mail->sendBookingConfirmedWithTicket($booking, $pdfPath);
        } catch (\Throwable $e) {
            Log::error('Ticket generation error: ' . $e->getMessage());
        }
    }
}

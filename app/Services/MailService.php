<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\Booking;

class MailService
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.hostinger.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'noreply@arifsiddikm.com';
        $this->mailer->Password   = 'SatuDua345!!';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port       = 465;
        $this->mailer->CharSet    = 'UTF-8';
        $this->mailer->setFrom('noreply@arifsiddikm.com', 'NginepYuk');
    }

    protected function send(string $to, string $toName, string $subject, string $body): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to, $toName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            \Log::error('MailService error: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }

    protected function sendWithAttachment(string $to, string $toName, string $subject, string $body, string $filePath, string $fileName): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->addAddress($to, $toName);
            $this->mailer->addAttachment($filePath, $fileName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            \Log::error('MailService attachment error: ' . $this->mailer->ErrorInfo);
            return false;
        }
    }

    // Notif ke admin: pesanan baru
    public function sendNewBookingToAdmin(Booking $booking): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'arifsiddikmuharam@gmail.com');
        $subject = '[NginepYuk] Pesanan Baru - ' . $booking->booking_code;
        $body = $this->templateNewBookingAdmin($booking);
        $this->send($adminEmail, 'Admin NginepYuk', $subject, $body);
    }

    // Notif ke pembeli: konfirmasi pesanan
    public function sendOrderConfirmationToUser(Booking $booking): void
    {
        $subject = '[NginepYuk] Konfirmasi Pesanan #' . $booking->booking_code;
        $body = $this->templateOrderConfirmation($booking);
        $this->send($booking->guest_email, $booking->guest_name, $subject, $body);
    }

    // Notif ke admin: bukti TF diupload
    public function sendTransferProofToAdmin(Booking $booking): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'arifsiddikmuharam@gmail.com');
        $subject = '[NginepYuk] Bukti Transfer Diterima - ' . $booking->booking_code;
        $body = $this->templateTransferProofAdmin($booking);
        $this->send($adminEmail, 'Admin NginepYuk', $subject, $body);
    }

    // Notif ke pembeli: terima kasih upload bukti
    public function sendTransferProofThanksToUser(Booking $booking): void
    {
        $subject = '[NginepYuk] Bukti Transfer Diterima - #' . $booking->booking_code;
        $body = $this->templateTransferProofUser($booking);
        $this->send($booking->guest_email, $booking->guest_name, $subject, $body);
    }

    // Notif ke admin: pembayaran midtrans selesai
    public function sendMidtransSuccessToAdmin(Booking $booking): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'arifsiddikmuharam@gmail.com');
        $subject = '[NginepYuk] Pembayaran Gateway Berhasil - ' . $booking->booking_code;
        $body = $this->templateMidtransSuccessAdmin($booking);
        $this->send($adminEmail, 'Admin NginepYuk', $subject, $body);
    }

    // Notif ke pembeli: booking dikonfirmasi + kirim tiket PDF
    public function sendBookingConfirmedWithTicket(Booking $booking, string $pdfPath): void
    {
        $subject = '[NginepYuk] Booking Dikonfirmasi & Tiket Reservasi - #' . $booking->booking_code;
        $body = $this->templateBookingConfirmed($booking);
        $this->sendWithAttachment(
            $booking->guest_email,
            $booking->guest_name,
            $subject,
            $body,
            $pdfPath,
            'Tiket-' . $booking->booking_code . '.pdf'
        );
    }

    // Notif ke admin: pesanan dikonfirmasi manual
    public function sendManualConfirmNotifToAdmin(Booking $booking): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'arifsiddikmuharam@gmail.com');
        $total      = 'Rp ' . number_format($booking->total_amount, 0, ',', '.');
        $subject    = '[NginepYuk] Pesanan Dikonfirmasi Manual - ' . $booking->booking_code;
        $body       = "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#10b981;padding:24px;color:white'>
                <h2 style='margin:0'>✅ NginepYuk — Pesanan Dikonfirmasi</h2>
            </div>
            <div style='padding:24px'>
                <p>Pesanan berikut telah <strong>dikonfirmasi secara manual</strong> oleh admin:</p>
                <table style='width:100%;border-collapse:collapse'>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Kode Booking</td><td style='padding:8px'>{$booking->booking_code}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Pemesan</td><td style='padding:8px'>{$booking->guest_name} ({$booking->guest_email})</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Properti</td><td style='padding:8px'>{$booking->property->name}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Check-in</td><td style='padding:8px'>{$booking->checkin_date->format('d M Y')}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Check-out</td><td style='padding:8px'>{$booking->checkout_date->format('d M Y')}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Total</td><td style='padding:8px;color:#10b981;font-weight:bold'>{$total}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Dikonfirmasi oleh</td><td style='padding:8px'>{$booking->confirmed_by}</td></tr>
                </table>
                <p style='margin-top:16px;color:#6b7280;font-size:13px'>Tiket PDF telah dikirim ke email pembeli.</p>
                <p><a href='" . url('/admin/bookings/' . $booking->id) . "' style='background:#10b981;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Lihat Detail Pesanan</a></p>
            </div>
        </div>";
        $this->send($adminEmail, 'Admin NginepYuk', $subject, $body);
    }

    // --- Templates ---

    private function templateNewBookingAdmin(Booking $booking): string
    {
        $total = 'Rp ' . number_format($booking->total_amount, 0, ',', '.');
        $method = $booking->payment_method === 'midtrans' ? 'Payment Gateway' : 'Transfer Bank';
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#1d4ed8;padding:24px;color:white'>
                <h2 style='margin:0'>🏨 NginepYuk — Pesanan Baru!</h2>
            </div>
            <div style='padding:24px'>
                <p>Ada pesanan baru masuk:</p>
                <table style='width:100%;border-collapse:collapse'>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Kode Booking</td><td style='padding:8px'>{$booking->booking_code}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Pemesan</td><td style='padding:8px'>{$booking->guest_name} ({$booking->guest_email})</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Properti</td><td style='padding:8px'>{$booking->property->name}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Check-in</td><td style='padding:8px'>{$booking->checkin_date->format('d M Y')}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Check-out</td><td style='padding:8px'>{$booking->checkout_date->format('d M Y')}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Total</td><td style='padding:8px;color:#1d4ed8;font-weight:bold'>{$total}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Metode Bayar</td><td style='padding:8px'>{$method}</td></tr>
                </table>
                <p style='margin-top:16px'><a href='" . url('/admin/bookings/' . $booking->id) . "' style='background:#1d4ed8;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Lihat Detail Pesanan</a></p>
            </div>
        </div>";
    }

    private function templateOrderConfirmation(Booking $booking): string
    {
        $total = 'Rp ' . number_format($booking->total_amount, 0, ',', '.');
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#1d4ed8;padding:24px;color:white'>
                <h2 style='margin:0'>🏨 NginepYuk — Konfirmasi Pesanan</h2>
            </div>
            <div style='padding:24px'>
                <p>Halo <strong>{$booking->guest_name}</strong>, pesanan Anda telah kami terima!</p>
                <table style='width:100%;border-collapse:collapse'>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Kode Booking</td><td style='padding:8px'>{$booking->booking_code}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Properti</td><td style='padding:8px'>{$booking->property->name}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Check-in</td><td style='padding:8px'>{$booking->checkin_date->format('d M Y')}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Check-out</td><td style='padding:8px'>{$booking->checkout_date->format('d M Y')}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Jumlah Malam</td><td style='padding:8px'>{$booking->nights} malam</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Total Pembayaran</td><td style='padding:8px;color:#1d4ed8;font-weight:bold'>{$total}</td></tr>
                </table>
                <p style='margin-top:16px;color:#6b7280'>Silakan lakukan pembayaran sesuai metode yang dipilih. Tiket reservasi akan dikirim setelah pembayaran dikonfirmasi.</p>
                <p><a href='" . url('/booking/' . $booking->booking_code) . "' style='background:#1d4ed8;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Cek Status Pesanan</a></p>
            </div>
            <div style='background:#f9fafb;padding:16px;text-align:center;color:#9ca3af;font-size:12px'>
                NginepYuk — Platform Reservasi Terpercaya | <a href='https://wa.me/" . env('ADMIN_WHATSAPP') . "' style='color:#1d4ed8'>Hubungi Admin</a>
            </div>
        </div>";
    }

    private function templateTransferProofAdmin(Booking $booking): string
    {
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#f59e0b;padding:24px;color:white'>
                <h2 style='margin:0'>⚡ NginepYuk — Bukti Transfer Masuk</h2>
            </div>
            <div style='padding:24px'>
                <p>Pembeli <strong>{$booking->guest_name}</strong> telah mengupload bukti transfer untuk pesanan <strong>{$booking->booking_code}</strong>.</p>
                <p>Mohon segera verifikasi pembayaran.</p>
                <p><a href='" . url('/admin/bookings/' . $booking->id) . "' style='background:#f59e0b;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Verifikasi Sekarang</a></p>
            </div>
        </div>";
    }

    private function templateTransferProofUser(Booking $booking): string
    {
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#10b981;padding:24px;color:white'>
                <h2 style='margin:0'>✅ NginepYuk — Terima Kasih!</h2>
            </div>
            <div style='padding:24px'>
                <p>Halo <strong>{$booking->guest_name}</strong>,</p>
                <p>Terima kasih telah mengupload bukti transfer untuk pesanan <strong>{$booking->booking_code}</strong>.</p>
                <p>Tim kami akan segera memverifikasi pembayaran Anda. Anda akan mendapat notifikasi dan tiket reservasi setelah pembayaran dikonfirmasi.</p>
            </div>
        </div>";
    }

    private function templateMidtransSuccessAdmin(Booking $booking): string
    {
        $total = 'Rp ' . number_format($booking->total_amount, 0, ',', '.');
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#10b981;padding:24px;color:white'>
                <h2 style='margin:0'>💳 NginepYuk — Pembayaran Gateway Berhasil</h2>
            </div>
            <div style='padding:24px'>
                <p>Pembayaran via payment gateway berhasil untuk pesanan <strong>{$booking->booking_code}</strong>.</p>
                <p>Properti: <strong>{$booking->property->name}</strong> | Total: <strong>{$total}</strong></p>
                <p><a href='" . url('/admin/bookings/' . $booking->id) . "' style='background:#10b981;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Lihat Detail</a></p>
            </div>
        </div>";
    }

    private function templateBookingConfirmed(Booking $booking): string
    {
        $total = 'Rp ' . number_format($booking->total_amount, 0, ',', '.');
        return "
        <div style='font-family:sans-serif;max-width:600px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>
            <div style='background:#1d4ed8;padding:24px;color:white'>
                <h2 style='margin:0'>🎉 NginepYuk — Booking Dikonfirmasi!</h2>
            </div>
            <div style='padding:24px'>
                <p>Halo <strong>{$booking->guest_name}</strong>,</p>
                <p>Selamat! Booking Anda telah <strong>dikonfirmasi</strong>. Tiket reservasi terlampir di email ini.</p>
                <table style='width:100%;border-collapse:collapse'>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Kode Booking</td><td style='padding:8px'>{$booking->booking_code}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Properti</td><td style='padding:8px'>{$booking->property->name}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Check-in</td><td style='padding:8px'>{$booking->checkin_date->format('d M Y')}</td></tr>
                    <tr style='background:#f9fafb'><td style='padding:8px;font-weight:bold;color:#374151'>Check-out</td><td style='padding:8px'>{$booking->checkout_date->format('d M Y')}</td></tr>
                    <tr><td style='padding:8px;font-weight:bold;color:#374151'>Total</td><td style='padding:8px;color:#1d4ed8;font-weight:bold'>{$total}</td></tr>
                </table>
                <p style='margin-top:16px;color:#6b7280'>Tunjukkan tiket ini saat check-in. Selamat menikmati perjalanan Anda! 🏨</p>
                <p><a href='https://wa.me/" . env('ADMIN_WHATSAPP') . "' style='background:#25d366;color:white;padding:10px 20px;border-radius:6px;text-decoration:none'>Hubungi Admin via WhatsApp</a></p>
            </div>
            <div style='background:#f9fafb;padding:16px;text-align:center;color:#9ca3af;font-size:12px'>
                NginepYuk — Platform Reservasi Terpercaya
            </div>
        </div>";
    }
}

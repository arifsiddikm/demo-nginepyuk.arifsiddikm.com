<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tiket - {{ $booking->booking_code }}</title>
<style>
* { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 0; box-sizing: border-box; font-size: 11px; }
body { background: #fff; color: #1e293b; padding: 16px; }

.ticket { max-width: 540px; margin: 0 auto; border: 1.5px solid #1d4ed8; border-radius: 10px; overflow: hidden; }
.header { background: #1d4ed8; color: white; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
.header h1 { font-size: 17px; font-weight: 900; }
.header p { font-size: 10px; opacity: .8; margin-top: 2px; }

.code-bar { background: #eff6ff; border-bottom: 1.5px dashed #93c5fd; padding: 10px 20px; text-align: center; }
.code-bar .code { font-size: 16px; font-weight: 900; color: #1d4ed8; letter-spacing: 1.5px; }
.code-bar .status { display: inline-block; background: #d1fae5; color: #065f46; padding: 2px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; margin-top: 4px; }

.body { padding: 16px 20px; }
.section-title { font-size: 9px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; margin-bottom: 8px; margin-top: 12px; }
.section-title:first-child { margin-top: 0; }

.row { display: flex; justify-content: space-between; margin-bottom: 5px; }
.row .lbl { color: #64748b; }
.row .val { font-weight: 600; color: #1e293b; text-align: right; max-width: 60%; }

.checkin-box { background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 8px 12px; margin: 8px 0; display: flex; justify-content: space-between; align-items: center; }
.checkin-box .side .lbl { color: #065f46; font-size: 9px; }
.checkin-box .side .val { color: #065f46; font-size: 13px; font-weight: 900; margin-top: 1px; }
.arrow { color: #16a34a; font-size: 16px; font-weight: 900; }

.total-box { background: #eff6ff; border: 1px solid #93c5fd; border-radius: 6px; padding: 8px 12px; margin: 8px 0; display: flex; justify-content: space-between; align-items: center; }
.total-box .lbl { color: #1e40af; font-weight: 700; }
.total-box .val { color: #1d4ed8; font-size: 15px; font-weight: 900; }

.divider { border: none; border-top: 1px dashed #e2e8f0; margin: 10px 0; }

.footer { background: #f8fafc; padding: 10px 20px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; }

/* Page 2 */
.page-break { page-break-before: always; padding-top: 16px; }
.instruction-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 12px; }
.instruction-box h3 { font-size: 12px; font-weight: 700; color: #1d4ed8; margin-bottom: 8px; }
.instruction-box ol, .instruction-box ul { padding-left: 16px; }
.instruction-box li { margin-bottom: 4px; color: #475569; line-height: 1.5; }
.highlight { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; }
.highlight p { color: #92400e; font-size: 10px; }
.contact-row { display: flex; justify-content: space-between; margin-top: 10px; }
.contact-item { text-align: center; background: #f8fafc; border-radius: 6px; padding: 8px 12px; flex: 1; margin: 0 4px; }
.contact-item .icon { font-size: 14px; margin-bottom: 3px; }
.contact-item p { font-size: 9px; color: #64748b; }
.contact-item a { font-size: 10px; font-weight: 600; color: #1d4ed8; }
</style>
</head>
<body>

{{-- HALAMAN 1: Tiket Utama --}}
<div class="ticket">
    <div class="header">
        <div>
            <h1>🏨 NginepYuk</h1>
            <p>Tiket Reservasi Resmi</p>
        </div>
        <div style="text-align:right;color:rgba(255,255,255,.8);font-size:9px;">
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="code-bar">
        <div class="code">{{ $booking->booking_code }}</div>
        <div><span class="status">✓ {{ strtoupper($booking->status_label) }}</span></div>
    </div>

    <div class="body">
        {{-- Properti --}}
        <p class="section-title">Properti</p>
        <div class="row"><span class="lbl">Nama</span><span class="val">{{ $booking->property->name }}</span></div>
        <div class="row"><span class="lbl">Kategori</span><span class="val">{{ $booking->property->category->name }}</span></div>
        <div class="row"><span class="lbl">Alamat</span><span class="val" style="text-align:right">{{ $booking->property->address }}, {{ $booking->property->city }}</span></div>

        {{-- Periode --}}
        <p class="section-title">Periode Menginap</p>
        <div class="checkin-box">
            <div class="side">
                <div class="lbl">Check-in</div>
                <div class="val">{{ $booking->checkin_date->format('d M Y') }}</div>
            </div>
            <div class="arrow">→</div>
            <div class="side" style="text-align:right">
                <div class="lbl">Check-out</div>
                <div class="val">{{ $booking->checkout_date->format('d M Y') }}</div>
            </div>
        </div>
        <div class="row"><span class="lbl">Durasi</span><span class="val">{{ $booking->nights }} Malam</span></div>
        <div class="row"><span class="lbl">Kamar</span><span class="val">{{ $booking->rooms }} Kamar</span></div>
        <div class="row"><span class="lbl">Tamu</span><span class="val">{{ $booking->guests }} Orang</span></div>

        {{-- Pemesan --}}
        <hr class="divider">
        <p class="section-title">Data Pemesan</p>
        <div class="row"><span class="lbl">Nama</span><span class="val">{{ $booking->guest_name }}</span></div>
        <div class="row"><span class="lbl">Email</span><span class="val">{{ $booking->guest_email }}</span></div>
        <div class="row"><span class="lbl">Telepon</span><span class="val">{{ $booking->guest_phone ?? '-' }}</span></div>

        {{-- Pembayaran --}}
        <hr class="divider">
        <p class="section-title">Pembayaran</p>
        <div class="row"><span class="lbl">Harga/malam</span><span class="val">Rp {{ number_format($booking->price_per_night,0,',','.') }}</span></div>
        <div class="row"><span class="lbl">Subtotal</span><span class="val">Rp {{ number_format($booking->subtotal,0,',','.') }}</span></div>
        <div class="row"><span class="lbl">PPN 11%</span><span class="val">Rp {{ number_format($booking->tax_amount,0,',','.') }}</span></div>
        <div class="total-box">
            <span class="lbl">Total Pembayaran</span>
            <span class="val">Rp {{ number_format($booking->total_amount,0,',','.') }}</span>
        </div>
        <div class="row"><span class="lbl">Metode</span><span class="val">{{ $booking->payment_method === 'midtrans' ? 'Pembayaran Otomatis' : 'Transfer Bank' }}</span></div>
        @if($booking->confirmed_at)
        <div class="row"><span class="lbl">Dikonfirmasi</span><span class="val">{{ $booking->confirmed_at->format('d M Y H:i') }}</span></div>
        @endif
    </div>

    <div class="footer">
        Tiket ini sah sebagai bukti reservasi resmi NginepYuk • noreply@arifsiddikm.com
    </div>
</div>

{{-- HALAMAN 2: Instruksi & Informasi Check-in --}}
<div class="page-break">
    <div class="ticket">
        <div class="header">
            <div>
                <h1>📋 Informasi Check-in</h1>
                <p>{{ $booking->booking_code }} — {{ $booking->property->name }}</p>
            </div>
        </div>
        <div class="body">

            <div class="highlight">
                <p><strong>⏰ Penting:</strong> Tunjukkan tiket ini (halaman 1) kepada resepsionis saat check-in. Simpan tiket ini dengan baik.</p>
            </div>

            <div class="instruction-box">
                <h3>🏨 Prosedur Check-in</h3>
                <ol>
                    <li>Tiba di properti pada atau setelah waktu check-in (biasanya pukul 14.00)</li>
                    <li>Tunjukkan tiket reservasi ini beserta kartu identitas (KTP/Paspor)</li>
                    <li>Lakukan pengisian formulir tamu jika diperlukan</li>
                    <li>Terima kunci / akses kamar dari resepsionis</li>
                </ol>
            </div>

            <div class="instruction-box">
                <h3>🚪 Prosedur Check-out</h3>
                <ol>
                    <li>Check-out sebelum pukul 12.00 (siang) di hari checkout</li>
                    <li>Serahkan kunci kamar ke resepsionis</li>
                    <li>Pastikan tidak ada barang bawaan yang tertinggal</li>
                    <li>Late check-out tersedia dengan biaya tambahan (konfirmasi ke properti)</li>
                </ol>
            </div>

            <div class="instruction-box">
                <h3>📌 Peraturan Umum</h3>
                <ul>
                    <li>Dilarang merokok di dalam kamar</li>
                    <li>Jaga kebersihan dan ketertiban selama menginap</li>
                    <li>Kerusakan fasilitas menjadi tanggung jawab tamu</li>
                    <li>Tamu tambahan di luar yang terdaftar dikenakan biaya tambahan</li>
                </ul>
            </div>

            <p style="font-size:9px;font-weight:700;color:#374151;margin-bottom:6px;">Hubungi Kami:</p>
            <div class="contact-row">
                <div class="contact-item">
                    <div class="icon">📧</div>
                    <p>Email Support</p>
                    <a>noreply@arifsiddikm.com</a>
                </div>
                <div class="contact-item">
                    <div class="icon">💬</div>
                    <p>WhatsApp Admin</p>
                    <a>+62 895-1439-2694</a>
                </div>
            </div>
        </div>
        <div class="footer">
            NginepYuk — Platform Reservasi Terpercaya Indonesia • {{ now()->format('Y') }}
        </div>
    </div>
</div>

</body>
</html>

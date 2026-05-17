@extends('layouts.app')
@section('title', 'Pembayaran #' . $booking->booking_code)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    @php
    $statusColors = [
        'pending'         => ['bg-yellow-50','border-yellow-300','text-yellow-800','fas fa-clock'],
        'waiting_payment' => ['bg-orange-50','border-orange-300','text-orange-800','fas fa-upload'],
        'paid_unverified' => ['bg-blue-50','border-blue-300','text-blue-800','fas fa-circle-notch fa-spin'],
        'confirmed'       => ['bg-green-50','border-green-300','text-green-800','fas fa-circle-check'],
        'completed'       => ['bg-teal-50','border-teal-300','text-teal-800','fas fa-check-double'],
        'expired'         => ['bg-gray-50','border-gray-300','text-gray-700','fas fa-ban'],
        'cancelled'       => ['bg-red-50','border-red-300','text-red-800','fas fa-xmark-circle'],
    ];
    $sc = $statusColors[$booking->status] ?? ['bg-gray-50','border-gray-200','text-gray-700','fas fa-circle'];
    @endphp

    <div class="border rounded-2xl p-5 mb-6 {{ $sc[0] }} border-{{ explode('-',$sc[1])[1] }}-300 flex items-center gap-4"
         style="border-color: var(--sc-border, #d1d5db); background: var(--sc-bg, #f9fafb);"
         class="{{ $sc[0] }} {{ $sc[1] }}">
        <i class="{{ $sc[3] }} text-3xl {{ $sc[2] }}"></i>
        <div>
            <p class="font-bold text-lg {{ $sc[2] }}">{{ $booking->status_label }}</p>
            <p class="text-sm {{ $sc[2] }} opacity-80">Kode: <strong>{{ $booking->booking_code }}</strong></p>
        </div>
        @if(in_array($booking->status, ['confirmed','completed']))
            <a href="{{ route('booking.ticket', $booking->booking_code) }}" class="ml-auto btn-success btn-sm">
                <i class="fas fa-download"></i> Download Tiket
            </a>
        @endif
    </div>

    <!-- Detail Pesanan -->
    <div class="card p-6 mb-6">
        <h2 class="font-bold text-slate-700 text-lg mb-5 flex items-center gap-2">
            <i class="fas fa-receipt text-blue-600"></i> Detail Pesanan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><p class="text-slate-400 mb-1">Properti</p><p class="font-semibold">{{ $booking->property->name }}</p></div>
            <div><p class="text-slate-400 mb-1">Lokasi</p><p class="font-semibold">{{ $booking->property->city }}</p></div>
            <div><p class="text-slate-400 mb-1">Check-in</p><p class="font-semibold">{{ $booking->checkin_date->translatedFormat('d M Y') }}</p></div>
            <div><p class="text-slate-400 mb-1">Check-out</p><p class="font-semibold">{{ $booking->checkout_date->translatedFormat('d M Y') }}</p></div>
            <div><p class="text-slate-400 mb-1">Durasi</p><p class="font-semibold">{{ $booking->nights }} Malam</p></div>
            <div><p class="text-slate-400 mb-1">Kamar</p><p class="font-semibold">{{ $booking->rooms }} Kamar</p></div>
            <div><p class="text-slate-400 mb-1">Pemesan</p><p class="font-semibold">{{ $booking->guest_name }}</p></div>
            <div><p class="text-slate-400 mb-1">Email</p><p class="font-semibold">{{ $booking->guest_email }}</p></div>
        </div>
        <div class="border-t border-slate-100 mt-5 pt-5 space-y-2 text-sm">
            <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>Rp {{ number_format($booking->subtotal,0,',','.') }}</span></div>
            <div class="flex justify-between text-slate-600"><span>PPN 11%</span><span>Rp {{ number_format($booking->tax_amount,0,',','.') }}</span></div>
            <div class="flex justify-between font-bold text-slate-800 text-base border-t pt-2">
                <span>Total</span>
                <span class="text-blue-700">Rp {{ number_format($booking->total_amount,0,',','.') }}</span>
            </div>
        </div>
    </div>

    @if($booking->expired_at && in_array($booking->status, ['pending','waiting_payment']))
    <div class="alert alert-warning mb-4">
        <i class="fas fa-clock"></i>
        <span>Batas waktu pembayaran: <strong id="countdown">...</strong></span>
    </div>
    @endif

    <!-- PAYMENT -->
    @if($booking->status === 'pending')

        @if($booking->payment_method === 'midtrans')
        <div class="card p-6 mb-6">
            <h2 class="font-bold text-slate-700 text-lg mb-2 flex items-center gap-2">
                <i class="fas fa-credit-card text-blue-600"></i> Pembayaran Otomatis
            </h2>
            <p class="text-sm text-slate-500 mb-5">Klik tombol di bawah untuk membuka jendela pembayaran. Pilih metode: kartu kredit, transfer bank virtual, GoPay, OVO, QRIS, dll.</p>
            <button id="pay-btn" onclick="startMidtransPayment()" class="btn-primary w-full justify-center py-4 text-base">
                <i class="fas fa-credit-card"></i> Bayar Sekarang — Rp {{ number_format($booking->total_amount,0,',','.') }}
            </button>
            <p class="text-center text-xs text-slate-400 mt-3"><i class="fas fa-shield-halved text-green-500"></i> Transaksi aman via Midtrans</p>
        </div>
        @endif

        @if($booking->payment_method === 'bank_transfer')
        <div class="card p-6 mb-6">
            <h2 class="font-bold text-slate-700 text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-building-columns text-blue-600"></i> Transfer Bank Manual
            </h2>
            @php $banksList = isset($banks) ? $banks : \App\Models\BankAccount::where('is_active', true)->get(); @endphp
            @if($banksList->count() > 0)
            <div class="space-y-3 mb-6">
                @foreach($banksList as $bank)
                <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-700">{{ $bank->bank_name }}</p>
                        <p class="text-blue-700 font-mono font-bold text-lg">{{ $bank->account_number }}</p>
                        <p class="text-xs text-slate-500">a.n. {{ $bank->account_name }}</p>
                    </div>
                    <button onclick="copyText('{{ $bank->account_number }}')" class="btn-secondary btn-sm">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                @endforeach
            </div>
            <div class="bg-blue-50 rounded-xl p-4 mb-5 text-sm">
                <p class="font-bold text-blue-800 mb-2">Instruksi:</p>
                <ol class="list-decimal list-inside space-y-1 text-blue-700">
                    <li>Transfer tepat <strong>Rp {{ number_format($booking->total_amount,0,',','.') }}</strong></li>
                    <li>Upload bukti transfer di bawah ini</li>
                    <li>Admin verifikasi dalam 1×24 jam</li>
                </ol>
            </div>
            @endif
            <form action="{{ route('booking.transfer', $booking->booking_code) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                    <input type="file" name="transfer_proof" accept="image/*" class="form-input" required>
                    <p class="text-xs text-slate-400 mt-1">JPG/PNG, maks. 3MB</p>
                </div>
                @error('transfer_proof')<p class="form-error">{{ $message }}</p>@enderror
                <button type="submit" class="btn-primary w-full justify-center py-3">
                    <i class="fas fa-upload"></i> Upload Bukti Transfer
                </button>
            </form>
        </div>
        @endif

    @elseif($booking->status === 'waiting_payment')
        <div class="card p-6 mb-6 text-center py-8">
            <i class="fas fa-clock-rotate-left text-4xl text-orange-400 mb-3"></i>
            <h3 class="font-bold text-slate-700 text-lg mb-2">Menunggu Verifikasi Admin</h3>
            <p class="text-slate-500 text-sm mb-4">Bukti transfer sudah diterima. Admin akan memverifikasi dalam 1×24 jam.</p>
            @if($booking->transfer_proof)
                <img src="{{ asset('storage/'.$booking->transfer_proof) }}" class="max-h-40 rounded-xl border border-slate-200 mx-auto" alt="Bukti TF">
            @endif
        </div>

    @elseif(in_array($booking->status, ['confirmed','completed']))
        <div class="card p-6 mb-6 text-center py-8">
            <i class="fas fa-circle-check text-5xl text-green-500 mb-3"></i>
            <h3 class="font-bold text-slate-700 text-xl mb-2">Booking Dikonfirmasi! 🎉</h3>
            <p class="text-slate-500 text-sm mb-5">Tiket reservasi sudah dikirim ke email Anda.</p>
            <a href="{{ route('booking.ticket', $booking->booking_code) }}" class="btn-success">
                <i class="fas fa-download"></i> Download Tiket PDF
            </a>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="https://wa.me/6289514392694?text=Halo, saya ingin menanyakan pesanan {{ $booking->booking_code }}"
           target="_blank" class="btn-success">
            <i class="fab fa-whatsapp"></i> Bantuan via WhatsApp
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ env('MIDTRANS_SNAP_JS_URL','https://app.sandbox.midtrans.com/snap/snap.js') }}"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
@if($booking->expired_at && in_array($booking->status, ['pending','waiting_payment']))
(function() {
    const end = new Date('{{ $booking->expired_at->toISOString() }}');
    const el  = document.getElementById('countdown');
    const t   = setInterval(() => {
        const diff = Math.max(0, Math.floor((end - Date.now()) / 1000));
        if (!diff) { clearInterval(t); el.textContent = 'Kadaluarsa'; return; }
        el.textContent = String(Math.floor(diff/60)).padStart(2,'0') + ':' + String(diff%60).padStart(2,'0');
    }, 1000);
})();
@endif

function copyText(text) {
    navigator.clipboard.writeText(text).then(() =>
        Swal.fire({ title:'Disalin!', text:text, icon:'success', timer:1500, showConfirmButton:false })
    );
}

async function startMidtransPayment() {
    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

    try {
        const res  = await fetch('{{ route("payment.snaptoken") }}', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
            body: JSON.stringify({ booking_code: '{{ $booking->booking_code }}' })
        });
        const data = await res.json();

        if (data.status && data.snaptoken) {
            window.snap.pay(data.snaptoken, {
                onSuccess: r => {
                    window.location.href = '{{ route("payment.finish") }}?order_id=' + r.order_id +
                        '&transaction_status=' + r.transaction_status + '&status_code=200';
                },
                onPending: () => Swal.fire({ title:'Pembayaran Pending', text:'Selesaikan pembayaran Anda.', icon:'info' }),
                onError:   () => Swal.fire({ title:'Gagal', text:'Pembayaran gagal. Coba lagi.', icon:'error' }),
                onClose:   () => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang — Rp {{ number_format($booking->total_amount,0,',','.') }}';
                }
            });
        } else {
            const errMsg = data.message || 'Gagal memuat payment gateway.';
            console.error('Riplabs error:', data);
            Swal.fire({
                title: 'Gagal Memuat Pembayaran',
                html: '<p class="text-sm text-slate-600">' + errMsg + '</p>' +
                      '<p class="text-xs text-slate-400 mt-2">Cek log Laravel untuk detail.</p>',
                icon: 'error'
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
        }
    } catch(e) {
        Swal.fire({ title:'Error', text:'Koneksi bermasalah. Coba lagi.', icon:'error' });
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
    }
}
</script>
@endpush
@endsection

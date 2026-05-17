@extends('layouts.app')
@section('title', 'Checkout Booking')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <nav class="text-sm text-slate-400 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('explore.show', $property->slug) }}" class="hover:text-blue-600">{{ $property->name }}</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-600">Checkout</span>
    </nav>
    <h1 class="section-title mb-8">Konfirmasi Booking</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <form action="{{ route('booking.store') }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="property_id"    value="{{ $property->id }}">
                <input type="hidden" name="checkin_date"   value="{{ $checkin }}">
                <input type="hidden" name="checkout_date"  value="{{ $checkout }}">

                @if($errors->any())
                <div class="alert alert-error mb-6">
                    <i class="fas fa-circle-exclamation"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
                @endif

                <!-- Booking Detail -->
                <div class="card p-6 mb-6">
                    <h2 class="font-bold text-slate-700 text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-days text-blue-600"></i> Detail Booking
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-blue-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 mb-1">Check-in</p>
                            <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($checkin)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 mb-1">Check-out</p>
                            <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($checkout)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 mb-1">Durasi</p>
                            <p class="font-bold text-slate-800" id="nights-display">{{ $nights }} Malam</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label class="form-label">Jumlah Kamar</label>
                            <select name="rooms" class="form-select" id="rooms-input">
                                @for($i=1;$i<=min(10,$property->total_rooms);$i++)
                                    <option value="{{ $i }}" {{ $rooms==$i?'selected':'' }}>{{ $i }} Kamar</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Jumlah Tamu</label>
                            <select name="guests" class="form-select">
                                @for($i=1;$i<=$property->max_guests;$i++)
                                    <option value="{{ $i }}">{{ $i }} Tamu</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Data Tamu -->
                <div class="card p-6 mb-6">
                    <h2 class="font-bold text-slate-700 text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i> Data Pemesan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="guest_name" value="{{ old('guest_name', Auth::user()?->name) }}" class="form-input" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="guest_email" value="{{ old('guest_email', Auth::user()?->email) }}" class="form-input" required>
                        </div>
                        <div class="form-group mb-0 md:col-span-2">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="guest_phone" value="{{ old('guest_phone', Auth::user()?->phone) }}" class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group mb-0 md:col-span-2">
                            <label class="form-label">Permintaan Khusus</label>
                            <textarea name="special_request" class="form-textarea" placeholder="Contoh: kamar non-smoking, lantai atas, dll.">{{ old('special_request') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="card p-6 mb-6">
                    <h2 class="font-bold text-slate-700 text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-blue-600"></i> Metode Pembayaran
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pembayaran Otomatis (Midtrans) -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="payment_method" value="midtrans" id="pay_midtrans" class="sr-only peer" checked>
                            <div class="border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-xl p-4 transition-all h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 flex items-center justify-center flex-shrink-0" id="dot-midtrans">
                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600 dot-inner"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700 text-sm">💳 Pembayaran Otomatis</p>
                                        <p class="text-xs text-slate-400">Langsung dikonfirmasi otomatis</p>
                                    </div>
                                </div>
                                <div class="flex gap-1.5 flex-wrap">
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">BCA VA</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">Mandiri VA</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">GoPay</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">OVO</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">Dana</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">QRIS</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">Kartu Kredit</span>
                                    <span class="text-xs bg-white border border-slate-200 px-2 py-1 rounded font-medium text-slate-500">Alfamart</span>
                                </div>
                            </div>
                        </label>

                        <!-- Transfer Bank Manual -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="payment_method" value="bank_transfer" id="pay_tf" class="sr-only peer">
                            <div class="border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-xl p-4 transition-all h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center flex-shrink-0" id="dot-tf">
                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600 dot-inner hidden"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700 text-sm">🏦 Transfer Bank Manual</p>
                                        <p class="text-xs text-slate-400">Konfirmasi oleh admin (1×24 jam)</p>
                                    </div>
                                </div>
                                @if($banks->count() > 0)
                                <div class="space-y-1">
                                    @foreach($banks as $bank)
                                    <p class="text-xs text-slate-600 bg-white rounded-lg px-2 py-1">
                                        <span class="font-bold">{{ $bank->bank_name }}</span>
                                        <span class="font-mono text-blue-700"> {{ $bank->account_number }}</span>
                                        <span class="text-slate-400"> ({{ $bank->account_name }})</span>
                                    </p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-4 text-base">
                    <i class="fas fa-lock"></i> Konfirmasi & Pesan Sekarang
                </button>
                <p class="text-center text-xs text-slate-400 mt-3"><i class="fas fa-shield-halved text-green-500"></i> Transaksi aman & terenkripsi</p>
            </form>
        </div>

        <!-- Summary Sidebar (live update) -->
        <div class="lg:col-span-1">
            <div class="card p-6 sticky top-20">
                <h3 class="font-bold text-slate-700 mb-4">Ringkasan Pesanan</h3>
                <div class="flex gap-3 mb-4">
                    <img src="{{ $property->getMainImageUrl() }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=60'">
                    <div>
                        <p class="font-semibold text-slate-700 text-sm line-clamp-2">{{ $property->name }}</p>
                        <p class="text-xs text-slate-400">{{ $property->city }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm border-t border-slate-100 pt-4">
                    <div class="flex justify-between text-slate-600">
                        <span>Harga/malam/kamar</span>
                        <span>{{ $property->formatted_price }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span id="summary-nights-label">{{ $nights }} malam × {{ $rooms }} kamar</span>
                        <span id="summary-subtotal">Rp {{ number_format($subtotal,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>PPN 11%</span>
                        <span id="summary-tax">Rp {{ number_format($tax,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-800 border-t border-slate-200 pt-2 mt-2 text-base">
                        <span>Total</span>
                        <span class="text-blue-700" id="summary-total">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs text-amber-700 flex items-start gap-2">
                        <i class="fas fa-clock mt-0.5 flex-shrink-0"></i>
                        Pesanan kadaluarsa dalam 30 menit jika belum dibayar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const BASE_PRICE = {{ $property->price_per_night }};
const NIGHTS = {{ $nights }};

function recalc() {
    const rooms = parseInt(document.getElementById('rooms-input').value) || 1;
    const sub   = BASE_PRICE * NIGHTS * rooms;
    const tax   = sub * 0.11;
    const tot   = sub + tax;
    document.getElementById('summary-nights-label').textContent = NIGHTS + ' malam × ' + rooms + ' kamar';
    document.getElementById('summary-subtotal').textContent     = 'Rp ' + Math.round(sub).toLocaleString('id');
    document.getElementById('summary-tax').textContent          = 'Rp ' + Math.round(tax).toLocaleString('id');
    document.getElementById('summary-total').textContent        = 'Rp ' + Math.round(tot).toLocaleString('id');
}
document.getElementById('rooms-input').addEventListener('change', recalc);
recalc();

// Radio visual fix
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.dot-inner').forEach(d => d.classList.add('hidden'));
        const dot = this.closest('label').querySelector('.dot-inner');
        if (dot) dot.classList.remove('hidden');
    });
});
</script>
@endpush
@endsection

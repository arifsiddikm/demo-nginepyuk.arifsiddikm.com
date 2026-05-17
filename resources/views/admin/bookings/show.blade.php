@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan')

@section('content')
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span class="badge badge-{{ $booking->status_color }} text-sm">{{ $booking->status_label }}</span>
    <span class="font-mono font-bold text-blue-700 text-sm">{{ $booking->booking_code }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">

        <!-- Booking Info -->
        <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Detail Booking</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-slate-400 mb-1">Properti</p><p class="font-semibold">{{ $booking->property->name }}</p></div>
                <div><p class="text-slate-400 mb-1">Kategori</p><p class="font-semibold">{{ $booking->property->category->name }}</p></div>
                <div><p class="text-slate-400 mb-1">Check-in</p><p class="font-semibold">{{ $booking->checkin_date->format('d M Y') }}</p></div>
                <div><p class="text-slate-400 mb-1">Check-out</p><p class="font-semibold">{{ $booking->checkout_date->format('d M Y') }}</p></div>
                <div><p class="text-slate-400 mb-1">Malam</p><p class="font-semibold">{{ $booking->nights }}</p></div>
                <div><p class="text-slate-400 mb-1">Kamar</p><p class="font-semibold">{{ $booking->rooms }}</p></div>
                <div><p class="text-slate-400 mb-1">Metode Bayar</p>
                    <p class="font-semibold">{{ $booking->payment_method === 'midtrans' ? '💳 Pembayaran Otomatis' : '🏦 Transfer Bank Manual' }}</p>
                </div>
                <div><p class="text-slate-400 mb-1">Tgl. Pesan</p><p class="font-semibold">{{ $booking->created_at->format('d M Y H:i') }}</p></div>
                @if($booking->special_request)
                <div class="col-span-2"><p class="text-slate-400 mb-1">Permintaan Khusus</p>
                    <p class="bg-slate-50 p-2 rounded text-slate-600">{{ $booking->special_request }}</p></div>
                @endif
            </div>
        </div>

        <!-- Guest Info -->
        <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Data Pemesan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-slate-400 mb-1">Nama</p><p class="font-semibold">{{ $booking->guest_name }}</p></div>
                <div><p class="text-slate-400 mb-1">Email</p><p class="font-semibold">{{ $booking->guest_email }}</p></div>
                <div><p class="text-slate-400 mb-1">Telepon</p><p class="font-semibold">{{ $booking->guest_phone ?? '-' }}</p></div>
                @if($booking->user)<div><p class="text-slate-400 mb-1">Akun</p><p class="font-semibold">{{ $booking->user->name }}</p></div>@endif
            </div>
        </div>

        <!-- Transfer Proof + Admin Upload -->
        <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Bukti Pembayaran</h3>

            @if($booking->transfer_proof)
            <div class="mb-5">
                <p class="text-xs text-slate-400 mb-2">Diupload pembeli: {{ $booking->transfer_uploaded_at?->format('d M Y H:i') }}</p>
                <img src="{{ asset('storage/'.$booking->transfer_proof) }}" class="max-h-64 rounded-xl border border-slate-200" alt="Bukti TF">
            </div>
            @endif

            <!-- Admin upload bukti — hanya tampil jika belum confirmed/completed -->
            @if(!in_array($booking->status, ['confirmed','completed','cancelled']))
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-sm font-semibold text-slate-700 mb-3">
                    <i class="fas fa-upload text-blue-600 mr-1"></i>
                    {{ $booking->transfer_proof ? 'Ganti / Tambah Bukti Transfer' : 'Upload Bukti Transfer (oleh Admin)' }}
                </p>
                <form action="{{ route('admin.bookings.upload-proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-end">
                        <div>
                            <label class="form-label text-xs">File Bukti (JPG/PNG, maks 3MB)</label>
                            <input type="file" name="transfer_proof" accept="image/*" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label text-xs">Sekalian konfirmasi pesanan?</label>
                            <div class="flex gap-3 mt-1">
                                <label class="form-radio">
                                    <input type="radio" name="auto_confirm" value="1" checked>
                                    <label class="text-sm text-green-700 font-semibold">Ya, konfirmasi selesai</label>
                                </label>
                                <label class="form-radio">
                                    <input type="radio" name="auto_confirm" value="0">
                                    <label class="text-sm text-slate-600">Tidak, hanya upload</label>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="confirmAction(this.closest('form'), 'Upload bukti dan lanjutkan?')"
                            class="btn btn-primary mt-3">
                        <i class="fas fa-upload"></i> Upload Bukti
                    </button>
                </form>
            </div>
            @endif
        </div>

        @if($booking->admin_notes)
        <div class="card p-4">
            <p class="text-xs text-slate-400 font-semibold mb-1">Catatan Admin:</p>
            <p class="text-sm text-slate-600">{{ $booking->admin_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Right: Pricing + Actions -->
    <div class="space-y-5">
        <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Ringkasan Harga</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-slate-600"><span>Harga/malam</span><span>Rp {{ number_format($booking->price_per_night,0,',','.') }}</span></div>
                <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>Rp {{ number_format($booking->subtotal,0,',','.') }}</span></div>
                <div class="flex justify-between text-slate-600"><span>PPN 11%</span><span>Rp {{ number_format($booking->tax_amount,0,',','.') }}</span></div>
                <div class="flex justify-between font-bold text-slate-800 border-t pt-2 text-base">
                    <span>Total</span><span class="text-blue-700">Rp {{ number_format($booking->total_amount,0,',','.') }}</span>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Aksi Admin</h3>
            <div class="space-y-3">
                @if(in_array($booking->status, ['waiting_payment','paid_unverified','pending']))
                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmAction(this.closest('form'), 'Konfirmasi pembayaran dan kirim tiket ke pembeli?')"
                            class="btn btn-success w-full justify-center">
                        <i class="fas fa-circle-check"></i> Konfirmasi Pembayaran
                    </button>
                </form>
                @endif

                @if($booking->status === 'confirmed')
                <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmAction(this.closest('form'), 'Tandai pesanan selesai?')"
                            class="btn btn-primary w-full justify-center">
                        <i class="fas fa-check-double"></i> Tandai Selesai
                    </button>
                </form>
                @endif

                @if(in_array($booking->status, ['confirmed','completed']))
                <a href="{{ route('booking.ticket', $booking->booking_code) }}" target="_blank"
                   class="btn btn-secondary w-full justify-center">
                    <i class="fas fa-download"></i> Download Tiket PDF
                </a>
                @endif

                <!-- Update Status Manual -->
                <div class="border-t border-slate-100 pt-3">
                    <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="status" class="form-select flex-1 text-xs">
                            @foreach(['pending','waiting_payment','paid_unverified','confirmed','completed','expired','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $booking->status===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="confirmAction(this.closest('form'), 'Ubah status pesanan?')" class="btn btn-warning btn-sm">Update</button>
                    </form>
                </div>

                @if(!in_array($booking->status, ['completed','cancelled']))
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label class="form-label text-xs">Alasan Pembatalan</label>
                        <input type="text" name="reason" class="form-input" placeholder="Opsional">
                    </div>
                    <button type="button" onclick="confirmAction(this.closest('form'), 'Batalkan pesanan ini?')"
                            class="btn btn-danger w-full justify-center">
                        <i class="fas fa-xmark"></i> Batalkan Pesanan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

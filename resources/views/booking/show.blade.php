{{-- Booking show uses same view as payment show --}}
@extends('layouts.app')
@section('title', 'Status Pesanan #' . $booking->booking_code)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    {{-- Status Banner --}}
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

    <div class="border rounded-2xl p-5 mb-6 {{ $sc[0] }} {{ $sc[1] }} border flex items-center gap-4">
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
        @if(in_array($booking->status, ['pending','waiting_payment']))
            <a href="{{ route('payment.show', $booking->booking_code) }}" class="ml-auto btn-primary btn-sm">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
            </a>
        @endif
    </div>

    {{-- Booking Detail --}}
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
            <div><p class="text-slate-400 mb-1">Nama Pemesan</p><p class="font-semibold">{{ $booking->guest_name }}</p></div>
            <div><p class="text-slate-400 mb-1">Email</p><p class="font-semibold">{{ $booking->guest_email }}</p></div>
        </div>
        <div class="border-t border-slate-100 mt-5 pt-5 space-y-2 text-sm">
            <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-slate-600"><span>PPN 11%</span><span>Rp {{ number_format($booking->tax_amount, 0, ',', '.') }}</span></div>
            <div class="flex justify-between font-bold text-slate-800 text-base border-t pt-2">
                <span>Total</span>
                <span class="text-blue-700">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if($booking->status === 'waiting_payment' && $booking->transfer_proof)
    <div class="card p-6 mb-6">
        <h3 class="font-semibold text-slate-700 mb-3">Bukti Transfer</h3>
        <img src="{{ asset('storage/'.$booking->transfer_proof) }}" class="max-h-48 rounded-xl border" alt="Bukti TF">
        <p class="text-xs text-slate-400 mt-2">Diupload {{ $booking->transfer_uploaded_at?->diffForHumans() }}. Menunggu verifikasi admin.</p>
    </div>
    @endif

    <div class="text-center">
        <a href="https://wa.me/{{ env('ADMIN_WHATSAPP') }}?text=Halo, saya ingin menanyakan pesanan {{ $booking->booking_code }}"
           target="_blank" class="btn-success">
            <i class="fab fa-whatsapp"></i> Hubungi Admin
        </a>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-slate-400 text-sm">Selamat datang di dashboard Anda</p>
        </div>
    </div>

    <!-- Sidebar Nav + Content -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Side Nav -->
        <aside class="w-full md:w-52 flex-shrink-0">
            <div class="card overflow-hidden">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium {{ request()->is('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-50' }} transition">
                    <i class="fas fa-gauge w-4"></i> Dashboard
                </a>
                <a href="{{ route('dashboard.bookings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium {{ request()->is('dashboard/pesanan') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-50' }} transition border-t border-slate-100">
                    <i class="fas fa-bookmark w-4"></i> Pesanan Saya
                </a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium {{ request()->is('dashboard/profil') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-50' }} transition border-t border-slate-100">
                    <i class="fas fa-user w-4"></i> Profil
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="card p-5 text-center">
                    <p class="text-3xl font-black text-blue-700">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">Total Pesanan</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="text-3xl font-black text-orange-500">{{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">Aktif</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="text-3xl font-black text-green-500">{{ $stats['completed'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">Selesai</p>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-700">Pesanan Terbaru</h3>
                    <a href="{{ route('dashboard.bookings') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                </div>
                @if($recentBookings->count() === 0)
                    <div class="p-10 text-center text-slate-400">
                        <i class="fas fa-bookmark text-4xl mb-3 text-slate-200"></i>
                        <p>Belum ada pesanan. <a href="{{ route('explore.index') }}" class="text-blue-600 underline">Jelajahi properti</a> sekarang!</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($recentBookings as $b)
                        <div class="flex items-center gap-4 px-6 py-4">
                            <img src="{{ $b->property->thumbnail_url }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-slate-700 truncate">{{ $b->property->name }}</p>
                                <p class="text-xs text-slate-400">{{ $b->checkin_date->format('d M') }} – {{ $b->checkout_date->format('d M Y') }} · {{ $b->nights }} malam</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="badge badge-{{ $b->status_color }} text-xs block mb-1">{{ $b->status_label }}</span>
                                <a href="{{ route('booking.show', $b->booking_code) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card lg:col-span-1">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase">Revenue</span>
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-coins text-blue-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-black text-slate-800">Rp {{ number_format($stats['revenue']/1000000, 1) }}Jt</p>
        <p class="text-xs text-slate-400 mt-1">Total pendapatan</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase">Pesanan</span>
            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-bookmark text-green-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['bookings'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Total pesanan</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase">Properti</span>
            <div class="w-9 h-9 bg-sky-100 rounded-xl flex items-center justify-center"><i class="fas fa-building text-sky-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['properties'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Properti aktif</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase">Pengguna</span>
            <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-purple-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['users'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Pengguna terdaftar</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase">Perlu Aksi</span>
            <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center"><i class="fas fa-bell text-orange-500 text-sm"></i></div>
        </div>
        <p class="text-2xl font-black text-orange-500">{{ $stats['pending'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Menunggu konfirmasi</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="card p-6 lg:col-span-2">
        <h3 class="font-bold text-slate-700 mb-4">Revenue 6 Bulan Terakhir</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
    <!-- Status Pie Chart -->
    <div class="card p-6">
        <h3 class="font-bold text-slate-700 mb-4">Status Pesanan</h3>
        <canvas id="statusChart" height="180"></canvas>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700">Pesanan Terbaru</h3>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pemesan</th>
                    <th>Properti</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $b)
                <tr>
                    <td class="font-mono text-xs font-semibold text-blue-700">{{ $b->booking_code }}</td>
                    <td>
                        <p class="font-medium text-sm">{{ $b->guest_name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->guest_email }}</p>
                    </td>
                    <td class="text-sm">{{ Str::limit($b->property->name??'-', 25) }}</td>
                    <td class="font-semibold text-sm">Rp {{ number_format($b->total_amount,0,',','.') }}</td>
                    <td><span class="badge badge-blue">{{ $b->payment_method === 'midtrans' ? 'Gateway' : 'Transfer' }}</span></td>
                    <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    <td><a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueChart->pluck('month')) !!},
        datasets: [{
            label: 'Revenue (Rp)',
            data: {!! json_encode($revenueChart->pluck('total')) !!},
            backgroundColor: 'rgba(29, 78, 216, 0.85)',
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'Jt' }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

// Status Doughnut
const statusCtx = document.getElementById('statusChart');
const statusData = {!! json_encode($statusChart) !!};
const statusColors = {
    pending: '#fbbf24', waiting_payment: '#f97316', paid_unverified: '#3b82f6',
    confirmed: '#10b981', completed: '#14b8a6', expired: '#94a3b8', cancelled: '#ef4444'
};
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusData.map(s => s.status),
        datasets: [{
            data: statusData.map(s => s.total),
            backgroundColor: statusData.map(s => statusColors[s.status] || '#94a3b8'),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } } }
    }
});
</script>
@endpush
@endsection

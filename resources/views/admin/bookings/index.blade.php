@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')
@section('page_title', 'Manajemen Pesanan')

@section('content')
<!-- Filter & Export -->
<div class="card p-4 mb-5">
    <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Kode / nama / email" style="width:200px">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select" style="width:160px">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="width:150px">
        </div>
        <div>
            <label class="form-label">Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="width:150px">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Reset</a>
        </div>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('admin.bookings.export.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
            <a href="{{ route('admin.bookings.export.excel', request()->query()) }}" class="btn btn-success btn-sm"><i class="fas fa-file-csv"></i> CSV</a>
        </div>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Pemesan</th><th>Properti</th><th>Periode</th><th>Total</th><th>Metode</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td class="text-slate-400">{{ $bookings->firstItem() + $loop->index }}</td>
                    <td class="font-mono text-xs font-bold text-blue-700">{{ $b->booking_code }}</td>
                    <td>
                        <p class="text-sm font-medium">{{ $b->guest_name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->guest_email }}</p>
                    </td>
                    <td class="text-sm max-w-[160px] truncate">{{ $b->property->name ?? '-' }}</td>
                    <td class="text-xs text-slate-600">{{ $b->checkin_date->format('d M Y') }}<br>→ {{ $b->checkout_date->format('d M Y') }}</td>
                    <td class="font-semibold text-sm">Rp {{ number_format($b->total_amount,0,',','.') }}</td>
                    <td><span class="badge badge-blue">{{ $b->payment_method === 'midtrans' ? 'Gateway' : 'Transfer' }}</span></td>
                    <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-10 text-slate-400">Tidak ada data pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $bookings->withQueryString()->links() }}
    </div>
</div>
@endsection

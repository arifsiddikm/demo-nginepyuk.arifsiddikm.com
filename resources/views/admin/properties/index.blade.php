@extends('layouts.admin')
@section('title', 'Manajemen Properti')
@section('page_title', 'Manajemen Properti')

@section('content')
<div class="flex justify-between items-center mb-5">
    <div></div>
    <a href="{{ route('admin.properties.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Properti</a>
</div>

<!-- Filter -->
<div class="card p-4 mb-5">
    <form action="{{ route('admin.properties.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Nama / kota" style="width:200px">
        </div>
        <div>
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select" style="width:150px">
                <option value="">Semua</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>#</th><th>Properti</th><th>Kategori</th><th>Kota</th><th>Harga/Malam</th><th>Kamar</th><th>Rating</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($properties as $p)
                <tr>
                    <td>{{ $properties->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->thumbnail_url }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            <span class="font-medium text-sm line-clamp-1 max-w-[180px]">{{ $p->name }}</span>
                        </div>
                    </td>
                    <td><span class="badge badge-blue">{{ $p->category->name }}</span></td>
                    <td class="text-sm">{{ $p->city }}</td>
                    <td class="text-sm font-semibold">Rp {{ number_format($p->price_per_night,0,',','.') }}</td>
                    <td class="text-sm">{{ $p->total_rooms }}</td>
                    <td class="text-sm">⭐ {{ number_format($p->rating_avg,1) }}</td>
                    <td><span class="badge {{ $p->status==='active'?'badge-green':'badge-gray' }}">{{ $p->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('admin.properties.edit', $p->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></a>
                            <form action="{{ route('admin.properties.destroy', $p->id) }}" method="POST" onsubmit="return false">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-10 text-slate-400">Tidak ada properti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $properties->withQueryString()->links() }}</div>
</div>
@endsection

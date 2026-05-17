@extends('layouts.admin')
@section('title', 'Manajemen Testimoni')
@section('page_title', 'Manajemen Testimoni')

@section('content')
<div class="card p-4 mb-5">
    <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select" style="width:150px">
                <option value="">Semua</option>
                <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Menunggu</option>
                <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Disetujui</option>
                <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead><tr><th>#</th><th>Pengguna</th><th>Properti</th><th>Rating</th><th>Ulasan</th><th>Status</th><th>Tgl</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($testimonials as $t)
            <tr>
                <td>{{ $testimonials->firstItem() + $loop->index }}</td>
                <td class="text-sm font-medium">{{ $t->user->name }}</td>
                <td class="text-sm max-w-[140px] truncate">{{ $t->property->name }}</td>
                <td>
                    <div class="flex gap-0.5">
                        @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star text-xs {{ $i<=$t->rating?'text-yellow-400':'text-slate-200' }}"></i>
                        @endfor
                    </div>
                </td>
                <td class="max-w-[200px]"><p class="text-xs text-slate-600 line-clamp-2">{{ $t->review }}</p></td>
                <td>
                    <span class="badge {{ $t->status==='approved'?'badge-green':($t->status==='rejected'?'badge-red':'badge-yellow') }}">
                        {{ $t->status === 'approved' ? 'Disetujui' : ($t->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                    </span>
                </td>
                <td class="text-xs text-slate-400">{{ $t->created_at->format('d M Y') }}</td>
                <td>
                    <div class="flex gap-1 flex-wrap">
                        @if($t->status !== 'approved')
                        <form action="{{ route('admin.testimonials.approve', $t->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmAction(this.closest('form'), 'Setujui ulasan ini?')" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                        </form>
                        @endif
                        @if($t->status !== 'rejected')
                        <form action="{{ route('admin.testimonials.reject', $t->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmAction(this.closest('form'), 'Tolak ulasan ini?')" class="btn btn-warning btn-sm"><i class="fas fa-xmark"></i></button>
                        </form>
                        @endif
                        <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return false">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-10 text-slate-400">Tidak ada testimoni.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-slate-100">{{ $testimonials->withQueryString()->links() }}</div>
</div>
@endsection

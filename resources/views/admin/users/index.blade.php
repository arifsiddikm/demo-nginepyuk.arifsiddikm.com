@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengguna</a>
</div>

<div class="card p-4 mb-5">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Nama / email" style="width:220px">
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-select" style="width:130px">
                <option value="">Semua</option>
                <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
                <option value="user" {{ request('role')==='user'?'selected':'' }}>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Username</th><th>Telepon</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">{{ strtoupper(substr($u->name,0,1)) }}</div>
                        <span class="font-medium text-sm">{{ $u->name }}</span>
                    </div>
                </td>
                <td class="text-sm">{{ $u->email }}</td>
                <td class="text-sm font-mono">{{ $u->username }}</td>
                <td class="text-sm">{{ $u->phone ?? '-' }}</td>
                <td><span class="badge {{ $u->role==='admin'?'badge-blue':'badge-green' }}">{{ ucfirst($u->role) }}</span></td>
                <td class="text-xs text-slate-400">{{ $u->created_at->format('d M Y') }}</td>
                <td>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return false">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-10 text-slate-400">Tidak ada pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-slate-100">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection

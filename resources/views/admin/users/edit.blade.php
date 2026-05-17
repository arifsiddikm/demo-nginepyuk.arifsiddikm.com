@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')

@section('content')
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<div class="max-w-xl">
    <div class="card p-6">
        @if($errors->any())
        <div class="alert alert-error mb-5">
            <i class="fas fa-circle-exclamation"></i>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Role <span class="text-red-500">*</span></label>
                <select name="role" class="form-select" required>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru <span class="text-slate-400 font-normal text-xs">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-input">
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1 justify-center"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page_title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

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

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-input" required>
            </div>
            @if(!isset($user))
            <div class="form-group">
                <label class="form-label">Username <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-input" required>
            </div>
            @endif
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Role <span class="text-red-500">*</span></label>
                <select name="role" class="form-select" required>
                    <option value="user" {{ old('role', $user->role ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-red-500">{{ !isset($user) ? '*' : '' }}</span></label>
                <input type="password" name="password" class="form-input" {{ !isset($user) ? 'required' : '' }} placeholder="Min. 8 karakter">
            </div>
            @if(!isset($user))
            <div class="form-group">
                <label class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            @endif

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i class="fas fa-save"></i> {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

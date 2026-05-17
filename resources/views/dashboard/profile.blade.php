@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-col md:flex-row gap-6">
        <aside class="w-full md:w-52 flex-shrink-0">
            <div class="card overflow-hidden">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 transition"><i class="fas fa-gauge w-4"></i> Dashboard</a>
                <a href="{{ route('dashboard.bookings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 transition border-t border-slate-100"><i class="fas fa-bookmark w-4"></i> Pesanan Saya</a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium bg-blue-600 text-white transition border-t border-slate-100"><i class="fas fa-user w-4"></i> Profil</a>
            </div>
        </aside>
        <div class="flex-1 space-y-6">
            <!-- Profile Update -->
            <div class="card p-6">
                <h2 class="font-bold text-slate-700 text-lg mb-5">Informasi Profil</h2>
                @if(session('success'))<div class="alert alert-success mb-4"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
                <form action="{{ route('dashboard.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-input" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Email</label>
                            <input type="email" value="{{ $user->email }}" class="form-input bg-slate-50" disabled>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group mb-0 md:col-span-2">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="address" value="{{ $user->address }}" class="form-input" placeholder="Alamat lengkap">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary mt-5"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </form>
            </div>

            <!-- Password Update -->
            <div class="card p-6">
                <h2 class="font-bold text-slate-700 text-lg mb-5">Ganti Password</h2>
                <form action="{{ route('dashboard.password.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group mb-0 md:col-span-2">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-warning mt-5"><i class="fas fa-key"></i> Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

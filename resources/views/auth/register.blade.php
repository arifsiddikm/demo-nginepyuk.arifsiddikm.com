@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-blue-50 to-sky-50">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-700 font-bold text-2xl mb-4">
                <svg width="36" height="36" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="8" fill="#1d4ed8"/><path d="M8 22V14L16 8L24 14V22H19V17H13V22H8Z" fill="white"/><circle cx="16" cy="14" r="2" fill="#93c5fd"/></svg>
                Nginep<span class="text-sky-500">Yuk</span>
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a></p>
        </div>

        <div class="card p-8">
            @if($errors->any())
                <div class="alert alert-error mb-6">
                    <i class="fas fa-circle-exclamation"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Nama lengkap Anda" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-input" placeholder="username_unik" required>
                    <p class="text-xs text-slate-400 mt-1">Hanya huruf, angka, dan tanda hubung (-/_)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="form-input pr-10" placeholder="Min. 8 karakter" required>
                        <button type="button" onclick="togglePwd('password', this)" class="absolute right-3 top-[10px] text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input pr-10" placeholder="Ulangi password" required>
                        <button type="button" onclick="togglePwd('password_confirmation', this)" class="absolute right-3 top-[10px] text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-checkbox mb-6">
                    <input type="checkbox" id="agree" required>
                    <label for="agree" class="text-sm text-slate-600">Saya setuju dengan syarat & ketentuan NginepYuk</label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
                    <i class="fas fa-user-plus"></i> Buat Akun
                </button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
@endpush
@endsection

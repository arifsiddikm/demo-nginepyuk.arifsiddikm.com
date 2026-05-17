@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-blue-50 to-sky-50">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-700 font-bold text-2xl mb-4">
                <svg width="36" height="36" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="8" fill="#1d4ed8"/><path d="M8 22V14L16 8L24 14V22H19V17H13V22H8Z" fill="white"/><circle cx="16" cy="14" r="2" fill="#93c5fd"/></svg>
                Nginep<span class="text-sky-500">Yuk</span>
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Masuk ke Akun Anda</h1>
            <p class="text-slate-500 text-sm mt-1">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar gratis</a></p>
        </div>

        <div class="card p-8">
            @if($errors->any())
                <div class="alert alert-error mb-6">
                    <i class="fas fa-circle-exclamation"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <!-- Autofill Admin Testing -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <p class="text-xs font-semibold text-amber-700 mb-2"><i class="fas fa-flask mr-1"></i> Demo / Testing</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="autofillAdmin()" class="text-xs bg-amber-100 hover:bg-amber-200 text-amber-800 font-semibold px-3 py-1.5 rounded-lg transition">
                        <i class="fas fa-shield-halved mr-1"></i> Isi Akun Admin
                    </button>
                    <button type="button" onclick="autofillUser()" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold px-3 py-1.5 rounded-lg transition">
                        <i class="fas fa-user mr-1"></i> Isi Akun User
                    </button>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" id="login-email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@contoh.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="relative">
                        <input type="password" id="login-password" name="password" class="form-input pr-10" placeholder="••••••••" required>
                        <button type="button" onclick="togglePwd('login-password', this)" class="absolute right-3 top-[10px] text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="form-checkbox">
                        <input type="checkbox" name="remember">
                        <label class="text-sm text-slate-600">Ingat saya</label>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
                    <i class="fas fa-right-to-bracket"></i> Masuk
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function autofillAdmin() {
    document.getElementById('login-email').value = 'admin@nginepyuk.com';
    document.getElementById('login-password').value = 'admin123';
}
function autofillUser() {
    document.getElementById('login-email').value = 'user@nginepyuk.com';
    document.getElementById('login-password').value = 'user123';
}
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endpush
@endsection

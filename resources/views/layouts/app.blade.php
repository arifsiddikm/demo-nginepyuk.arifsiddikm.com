<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NginepYuk') — Platform Reservasi Hotel & Villa</title>
    <meta name="description" content="@yield('meta_description', 'NginepYuk adalah platform booking hotel, villa, resort, kosan dan kontrakan terpercaya di Indonesia.')">
    <meta name="keywords" content="@yield('meta_keywords', 'booking hotel, reservasi villa, kosan, kontrakan, resort Indonesia')">
    <meta property="og:title" content="@yield('title', 'NginepYuk')">
    <meta property="og:description" content="@yield('meta_description', 'Platform booking penginapan terpercaya di Indonesia')">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1d4ed8;
            --primary-light: #3b82f6;
            --primary-bg: #eff6ff;
            --accent: #0ea5e9;
        }
        * { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }

        /* NAVBAR */
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 50; }
        .nav-link { color: #475569; font-weight: 500; padding: 6px 14px; border-radius: 8px; transition: all .2s; text-decoration: none; display: inline-block; }
        .nav-link:hover, .nav-link.active { background: var(--primary-bg); color: var(--primary); }
        .btn-nav-primary { background: var(--primary); color: white !important; padding: 8px 20px !important; border-radius: 8px; font-weight: 600; transition: all .2s; }
        .btn-nav-primary:hover { background: #1e40af; transform: translateY(-1px); }

        /* BUTTONS */
        .btn-primary { display: inline-flex; align-items: center; gap: 6px; background: var(--primary); color: white; padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-primary:hover { background: #1e40af; transform: translateY(-2px); box-shadow: 0 4px 16px rgba(29,78,216,.35); }
        .btn-secondary { display: inline-flex; align-items: center; gap: 6px; background: white; color: var(--primary); padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; border: 2px solid var(--primary); cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-secondary:hover { background: var(--primary-bg); }
        .btn-success { display: inline-flex; align-items: center; gap: 6px; background: #10b981; color: white; padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-success:hover { background: #059669; transform: translateY(-1px); }
        .btn-danger { display: inline-flex; align-items: center; gap: 6px; background: #ef4444; color: white; padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 6px 14px !important; font-size: 13px !important; border-radius: 8px !important; }
        .btn-warning { display: inline-flex; align-items: center; gap: 6px; background: #f59e0b; color: white; padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-warning:hover { background: #d97706; }

        /* FORM INPUTS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid #d1d5db; border-radius: 10px; font-size: 14px; color: #1e293b; background: white; transition: all .2s; outline: none; box-sizing: border-box; }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(29,78,216,.12); }
        .form-input::placeholder { color: #9ca3af; }
        .form-textarea { width: 100%; padding: 10px 14px; border: 1.5px solid #d1d5db; border-radius: 10px; font-size: 14px; color: #1e293b; background: white; transition: all .2s; outline: none; resize: vertical; min-height: 100px; box-sizing: border-box; }
        .form-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(29,78,216,.12); }
        .form-select { width: 100%; padding: 10px 14px; border: 1.5px solid #d1d5db; border-radius: 10px; font-size: 14px; color: #1e293b; background: white; transition: all .2s; outline: none; cursor: pointer; appearance: auto; box-sizing: border-box; }
        .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(29,78,216,.12); }
        .form-error { color: #ef4444; font-size: 12px; margin-top: 4px; }

        /* CHECKBOX & RADIO */
        .form-checkbox { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .form-checkbox input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); border-radius: 4px; cursor: pointer; flex-shrink: 0; }
        .form-checkbox label { font-size: 14px; color: #374151; cursor: pointer; }
        .form-radio { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .form-radio input[type="radio"] { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; flex-shrink: 0; }
        .form-radio label { font-size: 14px; color: #374151; cursor: pointer; }

        /* CARDS */
        .card { background: white; border-radius: 16px; box-shadow: 0 1px 8px rgba(0,0,0,.08); overflow: hidden; }
        .card-hover { transition: all .25s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(29,78,216,.15); }

        /* PROPERTY CARD */
        .property-card img { width: 100%; height: 220px; object-fit: cover; transition: transform .3s; }
        .property-card:hover img { transform: scale(1.05); }

        /* BADGE / STATUS */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-yellow  { background: #fef3c7; color: #92400e; }
        .badge-orange  { background: #ffedd5; color: #9a3412; }
        .badge-blue    { background: #dbeafe; color: #1e40af; }
        .badge-green   { background: #d1fae5; color: #065f46; }
        .badge-teal    { background: #ccfbf1; color: #0f766e; }
        .badge-gray    { background: #f1f5f9; color: #475569; }
        .badge-red     { background: #fee2e2; color: #991b1b; }

        /* ALERT */
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        /* SECTION TITLE */
        .section-title { font-size: 28px; font-weight: 800; color: #1e293b; }
        .section-subtitle { color: #64748b; font-size: 16px; margin-top: 6px; }

        /* FOOTER */
        .footer { background: #0f172a; color: #cbd5e1; }

        /* STARS */
        .stars { color: #f59e0b; }

        /* PAGINATION */
        .pagination-link { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; font-size: 14px; font-weight: 500; border: 1px solid #e2e8f0; color: #475569; transition: all .2s; }
        .pagination-link:hover, .pagination-link.active { background: var(--primary); color: white; border-color: var(--primary); }

        /* TABLE */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; border-bottom: 2px solid #e2e8f0; }
        .data-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #374151; }
        .data-table tr:hover td { background: #f8fafc; }

        /* HERO */
        .hero-gradient { background: linear-gradient(135deg, #1d4ed8 0%, #0ea5e9 60%, #38bdf8 100%); }

        /* MOBILE */
        @media (max-width: 768px) {
            .section-title { font-size: 22px; }
            .hide-mobile { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-700 font-bold text-xl">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="#1d4ed8"/>
                <path d="M8 22V14L16 8L24 14V22H19V17H13V22H8Z" fill="white"/>
                <circle cx="16" cy="14" r="2" fill="#93c5fd"/>
            </svg>
            <span>Nginep<span class="text-sky-500">Yuk</span></span>
        </a>

        <!-- Nav Links Desktop -->
        <div class="hidden md:flex items-center gap-2">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('explore.index') }}" class="nav-link {{ request()->is('jelajahi*') ? 'active' : '' }}">Jelajahi</a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center gap-3">
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50 transition">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-slate-700 hidden md:block">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-blue-50 rounded-t-xl font-medium text-blue-700"><i class="fas fa-shield-halved w-4"></i> Admin Panel</a>
                        @else
                            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-blue-50 rounded-t-xl"><i class="fas fa-gauge w-4 text-slate-400"></i> Dashboard</a>
                            <a href="{{ route('dashboard.bookings') }}" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-blue-50"><i class="fas fa-bookmark w-4 text-slate-400"></i> Pesanan Saya</a>
                            <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm hover:bg-blue-50"><i class="fas fa-user w-4 text-slate-400"></i> Profil</a>
                        @endif
                        <hr class="border-slate-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmLogout(this)" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">
                                <i class="fas fa-right-from-bracket w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn-nav-primary nav-link">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<!-- FLASH MESSAGES -->
<div class="max-w-7xl mx-auto px-4 mt-4" id="flash-container">
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info"><i class="fas fa-circle-info"></i> {{ session('info') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning"><i class="fas fa-triangle-exclamation"></i> {{ session('warning') }}</div>
    @endif
</div>

@yield('content')

<!-- FOOTER -->
<footer class="footer py-12" style="margin-top:0;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div>
                <div class="flex items-center gap-2 text-white font-bold text-xl mb-4">
                    <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="#3b82f6"/>
                        <path d="M8 22V14L16 8L24 14V22H19V17H13V22H8Z" fill="white"/>
                        <circle cx="16" cy="14" r="2" fill="#93c5fd"/>
                    </svg>
                    <span>Nginep<span class="text-sky-400">Yuk</span></span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">Platform booking penginapan terpercaya — hotel, villa, resort, kosan & kontrakan di seluruh Indonesia.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('explore.index') }}" class="text-slate-400 hover:text-white transition">Jelajahi Properti</a></li>
                    <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-white transition">Daftar Akun</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Kategori</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('explore.index', ['category'=>'hotel']) }}" class="text-slate-400 hover:text-white transition">Hotel</a></li>
                    <li><a href="{{ route('explore.index', ['category'=>'villa']) }}" class="text-slate-400 hover:text-white transition">Villa</a></li>
                    <li><a href="{{ route('explore.index', ['category'=>'resort']) }}" class="text-slate-400 hover:text-white transition">Resort</a></li>
                    <li><a href="{{ route('explore.index', ['category'=>'kosan']) }}" class="text-slate-400 hover:text-white transition">Kosan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Kontak</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2 text-slate-400"><i class="fas fa-envelope w-4 text-sky-400"></i> noreply@arifsiddikm.com</li>
                    <li class="flex items-center gap-2 text-slate-400"><i class="fab fa-whatsapp w-4 text-green-400"></i>
                        <a href="https://wa.me/{{ env('ADMIN_WHATSAPP') }}" class="hover:text-white transition">Chat WhatsApp</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 text-center text-slate-500 text-sm">
            &copy; {{ date('Y') }} NginepYuk. Hak cipta dilindungi undang-undang.
        </div>
    </div>
</footer>

<!-- FLOATING WHATSAPP BUTTON -->
<a href="https://wa.me/6289514392694" target="_blank" rel="noopener"
   style="position:fixed;bottom:24px;right:24px;z-index:999;width:56px;height:56px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,.5);transition:transform .2s,box-shadow .2s;"
   onmouseover="this.style.transform='scale(1.1)';this.style.boxShadow='0 6px 28px rgba(37,211,102,.65)'"
   onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 20px rgba(37,211,102,.5)'"
   title="Chat Admin via WhatsApp">
    <i class="fab fa-whatsapp" style="color:white;font-size:28px;line-height:1;"></i>
</a>

<script>
function confirmLogout(btn) {
    Swal.fire({
        title: 'Yakin mau logout?',
        text: 'Anda akan keluar dari akun Anda.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            btn.closest('form').submit();
        }
    });
}

// Auto hide flash messages
setTimeout(() => {
    const flash = document.getElementById('flash-container');
    if (flash) flash.style.opacity = '0', flash.style.transition = 'opacity .5s', setTimeout(() => flash.style.display = 'none', 500);
}, 5000);
</script>
@stack('scripts')
</body>
</html>

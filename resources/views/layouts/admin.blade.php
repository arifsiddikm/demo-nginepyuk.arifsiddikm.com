<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — NginepYuk Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #f1f5f9; }
        .sidebar { width: 260px; min-height: 100vh; background: #0f172a; color: #cbd5e1; position: fixed; top: 0; left: 0; z-index: 40; transition: transform .3s; }
        .sidebar-logo { padding: 20px 24px; border-bottom: 1px solid #1e293b; }
        .sidebar-nav { padding: 16px 12px; }
        .sidebar-group-label { font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 1px; padding: 12px 12px 6px; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px; font-size: 14px; color: #94a3b8; font-weight: 500; text-decoration: none; transition: all .2s; margin-bottom: 2px; }
        .sidebar-link i { width: 18px; text-align: center; font-size: 15px; }
        .sidebar-link:hover { background: #1e293b; color: #e2e8f0; }
        .sidebar-link.active { background: #1d4ed8; color: white; }
        .topbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 14px 24px; display: flex; align-items: center; justify-content: between; gap: 16px; position: sticky; top: 0; z-index: 30; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .page-header { padding: 28px 32px 0; margin-bottom: 6px; }
        .page-content { padding: 20px 32px 32px; }

        /* Form Elements */
        .form-label { display: block; font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 5px; }
        .form-input { width: 100%; padding: 9px 13px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; color: #1e293b; background: white; transition: border-color .2s; outline: none; box-sizing: border-box; }
        .form-input:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,.1); }
        .form-textarea { width: 100%; padding: 9px 13px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; color: #1e293b; background: white; transition: border-color .2s; outline: none; resize: vertical; min-height: 100px; box-sizing: border-box; }
        .form-textarea:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,.1); }
        .form-select { width: 100%; padding: 9px 13px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; color: #1e293b; background: white; outline: none; cursor: pointer; box-sizing: border-box; }
        .form-select:focus { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,.1); }
        .form-error { color: #ef4444; font-size: 12px; margin-top: 4px; }
        .form-checkbox { display: flex; align-items: center; gap: 8px; }
        .form-checkbox input[type="checkbox"] { width: 16px; height: 16px; accent-color: #1d4ed8; cursor: pointer; }
        .form-radio { display: flex; align-items: center; gap: 8px; }
        .form-radio input[type="radio"] { width: 16px; height: 16px; accent-color: #1d4ed8; cursor: pointer; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; border: none; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-primary { background: #1d4ed8; color: white; }
        .btn-primary:hover { background: #1e40af; transform: translateY(-1px); }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-outline { background: transparent; color: #1d4ed8; border: 1.5px solid #1d4ed8; }
        .btn-outline:hover { background: #eff6ff; }
        .btn-sm { padding: 6px 12px !important; font-size: 12px !important; }

        /* Cards */
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 6px rgba(0,0,0,.07); }

        /* Stats widget */
        .stat-card { background: white; border-radius: 14px; padding: 22px; box-shadow: 0 1px 6px rgba(0,0,0,.07); }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; border-bottom: 2px solid #e2e8f0; }
        .data-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #374151; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafc; }

        /* Badge */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-yellow  { background: #fef3c7; color: #92400e; }
        .badge-orange  { background: #ffedd5; color: #9a3412; }
        .badge-blue    { background: #dbeafe; color: #1e40af; }
        .badge-green   { background: #d1fae5; color: #065f46; }
        .badge-teal    { background: #ccfbf1; color: #0f766e; }
        .badge-gray    { background: #f1f5f9; color: #475569; }
        .badge-red     { background: #fee2e2; color: #991b1b; }

        /* Alert */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px; font-size: 13px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .page-header, .page-content { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg">
            <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
                <rect width="32" height="32" rx="8" fill="#3b82f6"/>
                <path d="M8 22V14L16 8L24 14V22H19V17H13V22H8Z" fill="white"/>
            </svg>
            <span>Nginep<span class="text-sky-400">Yuk</span> <span class="text-xs text-slate-400 font-normal">Admin</span></span>
        </a>
    </div>
    <nav class="sidebar-nav overflow-y-auto" style="max-height:calc(100vh - 80px)">
        <div class="sidebar-group-label">Overview</div>
        <a href="{{ url('/admin/dashboard') }}" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"><i class="fas fa-gauge"></i> Dashboard</a>

        <div class="sidebar-group-label">Manajemen</div>
        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->is('admin/bookings*') ? 'active' : '' }}"><i class="fas fa-bookmark"></i> Pesanan</a>
        <a href="{{ route('admin.properties.index') }}" class="sidebar-link {{ request()->is('admin/properties*') ? 'active' : '' }}"><i class="fas fa-building"></i> Properti</a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Pengguna</a>
        <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}"><i class="fas fa-star"></i> Testimoni</a>

        <div class="sidebar-group-label">Laporan</div>
        <a href="{{ route('admin.bookings.export.pdf') }}" class="sidebar-link" target="_blank"><i class="fas fa-file-pdf text-red-400"></i> Export PDF</a>
        <a href="{{ route('admin.bookings.export.excel') }}" class="sidebar-link" target="_blank"><i class="fas fa-file-csv text-green-400"></i> Export CSV</a>

        <div class="sidebar-group-label">Akun</div>
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank"><i class="fas fa-arrow-up-right-from-square"></i> Lihat Website</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-1">
            @csrf
            <button type="button" onclick="confirmLogout(this)" class="sidebar-link w-full text-left text-red-400 hover:bg-red-950">
                <i class="fas fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </nav>
</aside>

<!-- MAIN -->
<div class="main-content">
    <!-- TOPBAR -->
    <div class="topbar">
        <button onclick="toggleSidebar()" class="md:hidden text-slate-500 hover:text-slate-700 mr-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="flex-1">
            <h1 class="text-base font-semibold text-slate-700">@yield('page_title', 'Dashboard')</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500 hidden md:block">{{ Auth::user()->name }}</span>
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- FLASH -->
    <div class="page-header">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif
    </div>

    <!-- CONTENT -->
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
function confirmLogout(btn) {
    Swal.fire({
        title: 'Yakin mau logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) btn.closest('form').submit(); });
}
function confirmDelete(form) {
    Swal.fire({
        title: 'Hapus data ini?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) form.submit(); });
}
function confirmAction(form, msg) {
    Swal.fire({
        title: 'Konfirmasi',
        text: msg || 'Lanjutkan aksi ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1d4ed8',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) form.submit(); });
}
</script>
@stack('scripts')
</body>
</html>

@extends('layouts.app')

@section('title', 'NginepYuk')
@section('meta_description', 'Platform booking hotel, villa, resort, kosan dan kontrakan terpercaya di Indonesia. Pesan sekarang, bayar mudah!')

@section('content')

<!-- HERO -->
<section class="hero-gradient text-white py-20 px-4">
    <div class="max-w-5xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
            <i class="fas fa-star text-yellow-300"></i> Platform Booking Terpercaya #1 Indonesia
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-5 leading-tight">
            Temukan Penginapan<br><span class="text-sky-200">Impian Anda</span>
        </h1>
        <p class="text-lg text-blue-100 mb-10 max-w-2xl mx-auto">Hotel, villa, resort, kosan, kontrakan — tersebar di seluruh Indonesia. Booking mudah, aman, dan terpercaya.</p>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl p-4 md:p-6 shadow-2xl max-w-3xl mx-auto text-left">
            <form action="{{ route('explore.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <label class="form-label text-slate-700">Cari Properti / Kota</label>
                        <div class="relative">
                            <input type="text" name="q" placeholder="Contoh: Bali, hotel Jakarta..." class="form-input pl-10">
                            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-slate-700">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full justify-center py-[10px]">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-8 mt-10 text-white/90">
            <div class="text-center"><div class="text-3xl font-black">{{ number_format($stats['properties']) }}+</div><div class="text-sm text-blue-200">Properti</div></div>
            <div class="w-px bg-white/20 h-12 self-center hidden md:block"></div>
            <div class="text-center"><div class="text-3xl font-black">{{ number_format($stats['cities']) }}+</div><div class="text-sm text-blue-200">Kota</div></div>
            <div class="w-px bg-white/20 h-12 self-center hidden md:block"></div>
            <div class="text-center"><div class="text-3xl font-black">{{ number_format($stats['bookings']) }}+</div><div class="text-sm text-blue-200">Tamu Puas</div></div>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="section-title">Jelajahi Berdasarkan Kategori</h2>
            <p class="section-subtitle">Temukan properti sesuai kebutuhan Anda</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('explore.index', ['category' => $cat->slug]) }}" class="group flex flex-col items-center p-6 rounded-2xl border-2 border-transparent hover:border-blue-200 hover:bg-blue-50 transition-all duration-200 text-center">
                <div class="w-14 h-14 bg-blue-100 group-hover:bg-blue-600 rounded-2xl flex items-center justify-center mb-3 transition-all duration-200">
                    <i class="fas fa-building text-blue-600 group-hover:text-white text-xl transition-all"></i>
                </div>
                <span class="font-semibold text-slate-700 text-sm">{{ $cat->name }}</span>
                <span class="text-xs text-slate-400 mt-1">{{ $cat->properties_count }} properti</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURED -->
<section class="py-16 px-4 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="section-title">Properti Terpopuler</h2>
                <p class="section-subtitle">Rating terbaik dari tamu kami</p>
            </div>
            <a href="{{ route('explore.index') }}" class="btn-secondary btn-sm hidden md:flex">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featured as $p)
            <a href="{{ route('explore.show', $p->slug) }}" class="card card-hover property-card block group">
                <div class="overflow-hidden h-[220px]">
                    <img src="{{ $p->getMainImageUrl() }}" alt="{{ $p->name }}" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-3 py-1 rounded-full">{{ $p->category->name }}</span>
                        <span class="text-xs text-slate-400">{{ $p->city }}</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base mb-1 line-clamp-1">{{ $p->name }}</h3>
                    <div class="flex items-center gap-1 text-sm text-slate-500 mb-3">
                        <i class="fas fa-location-dot text-blue-400 text-xs"></i> {{ $p->address }}
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-blue-700 font-bold text-lg">{{ $p->formatted_price }}</span>
                            <span class="text-slate-400 text-sm">/malam</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                            <span class="text-sm font-semibold text-slate-700">{{ number_format($p->rating_avg, 1) }}</span>
                            <span class="text-xs text-slate-400">({{ $p->rating_count }})</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-8 md:hidden">
            <a href="{{ route('explore.index') }}" class="btn-secondary">Lihat Semua Properti</a>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="section-title mb-3">Cara Kerja NginepYuk</h2>
        <p class="section-subtitle mb-12">Pesan penginapan dalam 3 langkah mudah</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="relative">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white text-2xl font-black">1</div>
                <h3 class="font-bold text-slate-800 mb-2">Pilih Properti</h3>
                <p class="text-slate-500 text-sm">Jelajahi ribuan pilihan hotel, villa, dan properti lainnya. Filter sesuai budget dan kebutuhan.</p>
            </div>
            <div class="relative">
                <div class="w-16 h-16 bg-sky-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white text-2xl font-black">2</div>
                <h3 class="font-bold text-slate-800 mb-2">Booking & Bayar</h3>
                <p class="text-slate-500 text-sm">Isi detail tamu, pilih tanggal, dan bayar dengan payment gateway atau transfer bank.</p>
            </div>
            <div class="relative">
                <div class="w-16 h-16 bg-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white text-2xl font-black">3</div>
                <h3 class="font-bold text-slate-800 mb-2">Terima Tiket</h3>
                <p class="text-slate-500 text-sm">Tiket reservasi digital dikirim ke email. Tunjukkan saat check-in dan nikmati perjalanan!</p>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONI -->
@if($testimonials->count() > 0)
<section class="py-16 px-4 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="section-title">Kata Mereka</h2>
            <p class="section-subtitle">Ulasan nyata dari tamu kami</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($t->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-slate-700">{{ $t->user->name }}</div>
                        <div class="text-xs text-slate-400">{{ $t->property->name }}</div>
                    </div>
                </div>
                <div class="stars text-sm mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $t->rating ? '' : '-half-stroke' }}"></i>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed">"{{ Str::limit($t->review, 150) }}"</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 40%, #1d4ed8 70%, #0ea5e9 100%); position:relative; overflow:hidden; margin-bottom:0;">
    <!-- decorative blobs -->
    <div style="position:absolute;top:-80px;right:-80px;width:300px;height:300px;background:radial-gradient(circle,rgba(99,102,241,.3),transparent 70%);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;left:-60px;width:250px;height:250px;background:radial-gradient(circle,rgba(14,165,233,.25),transparent 70%);border-radius:50%;pointer-events:none;"></div>
    <div class="max-w-3xl mx-auto px-4 py-20 text-center" style="position:relative;z-index:1;">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold mb-6" style="background:rgba(255,255,255,.12);color:#bfdbfe;border:1px solid rgba(255,255,255,.2);">
            ✨ Bergabung dengan 10.000+ tamu puas
        </div>
        <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:white;line-height:1.2;margin-bottom:16px;">
            Siap Memulai<br><span style="background:linear-gradient(90deg,#93c5fd,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Perjalanan Impian?</span>
        </h2>
        <p style="color:#bfdbfe;font-size:1.05rem;margin-bottom:36px;max-width:500px;margin-left:auto;margin-right:auto;line-height:1.7;">
            Daftar gratis dan temukan ribuan pilihan hotel, villa, resort, dan penginapan terbaik di seluruh Indonesia.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            @guest
                <a href="{{ route('register') }}" style="background:white;color:#1d4ed8;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;display:inline-flex;align-items:center;gap:8px;transition:all .2s;text-decoration:none;box-shadow:0 4px 20px rgba(0,0,0,.2);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.3)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,.2)'">
                    <i class="fas fa-user-plus"></i> Daftar Gratis Sekarang
                </a>
                <a href="{{ route('explore.index') }}" style="background:rgba(255,255,255,.12);color:white;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;display:inline-flex;align-items:center;gap:8px;border:1.5px solid rgba(255,255,255,.3);transition:all .2s;text-decoration:none;" onmouseover="this.style.background='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
                    <i class="fas fa-compass"></i> Jelajahi Properti
                </a>
            @else
                <a href="{{ route('explore.index') }}" style="background:white;color:#1d4ed8;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;display:inline-flex;align-items:center;gap:8px;transition:all .2s;text-decoration:none;box-shadow:0 4px 20px rgba(0,0,0,.2);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                    <i class="fas fa-compass"></i> Jelajahi Sekarang
                </a>
            @endguest
        </div>
        <!-- stats row -->
        <div style="display:flex;justify-content:center;gap:40px;margin-top:40px;flex-wrap:wrap;">
            <div style="text-align:center;color:rgba(255,255,255,.7);">
                <div style="font-size:1.6rem;font-weight:900;color:white;">15+</div>
                <div style="font-size:.75rem;margin-top:2px;">Kota Tersedia</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.2);"></div>
            <div style="text-align:center;color:rgba(255,255,255,.7);">
                <div style="font-size:1.6rem;font-weight:900;color:white;">500+</div>
                <div style="font-size:.75rem;margin-top:2px;">Properti Pilihan</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.2);"></div>
            <div style="text-align:center;color:rgba(255,255,255,.7);">
                <div style="font-size:1.6rem;font-weight:900;color:white;">⭐ 4.8</div>
                <div style="font-size:.75rem;margin-top:2px;">Rating Rata-rata</div>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', 'NginepYuk — Booking Hotel, Villa & Penginapan Terbaik')
@section('meta_description', 'Platform booking hotel, villa, resort, kosan dan kontrakan terpercaya di Indonesia. Harga terbaik, tiket langsung ke email.')

@section('content')

<!-- ============================================================
     HERO — full viewport, background image slider + overlay
     ============================================================ -->
<section id="hero" style="min-height:92vh;position:relative;overflow:hidden;display:flex;align-items:center;">

    <!-- Background image slider -->
    <div id="hero-bg" style="position:absolute;inset:0;z-index:0;">
        @php
        $heroBgs = [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600&q=85',
            'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=1600&q=85',
            'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1600&q=85',
            'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1600&q=85',
        ];
        @endphp
        @foreach($heroBgs as $i => $bg)
        <div class="hero-bg-slide" style="
            position:absolute;inset:0;
            background:url('{{ $bg }}') center/cover no-repeat;
            opacity:{{ $i===0?1:0 }};
            transition:opacity 1.2s ease;
            transform:scale(1.05);
            animation:hero-zoom {{ 20+$i*2 }}s ease-in-out infinite alternate;
        "></div>
        @endforeach
        <!-- Dark gradient overlay -->
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(7,11,40,.82) 0%,rgba(12,28,80,.72) 40%,rgba(2,80,120,.55) 80%,rgba(0,0,0,.4) 100%);"></div>
        <!-- Bottom fade -->
        <div style="position:absolute;bottom:0;left:0;right:0;height:180px;background:linear-gradient(to top,#f8fafc,transparent);"></div>
    </div>

    <!-- Content -->
    <div class="max-w-6xl mx-auto px-4 w-full" style="position:relative;z-index:2;padding-top:60px;padding-bottom:80px;">
        <div style="display:grid;grid-template-columns:1fr;gap:40px;align-items:center;">

            <!-- Left text -->
            <div style="max-width:680px;">
                <div class="hfu" style="margin-bottom:20px;">
                    <span style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);backdrop-filter:blur(8px);color:#bae6fd;font-size:13px;font-weight:600;padding:7px 18px;border-radius:50px;">
                        <span style="width:7px;height:7px;background:#34d399;border-radius:50%;display:inline-block;box-shadow:0 0 10px #34d399;animation:pdot 2s infinite;"></span>
                        Terpercaya #1 di Indonesia
                    </span>
                </div>

                <h1 class="hfu" style="animation-delay:.1s;font-size:clamp(2rem,5vw,3.6rem);font-weight:900;color:white;line-height:1.12;letter-spacing:-1.5px;margin-bottom:20px;">
                    Temukan &amp; Booking<br>
                    <span style="background:linear-gradient(90deg,#7dd3fc,#38bdf8,#6ee7b7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Penginapan Impian</span><br>
                    <span style="font-size:.6em;font-weight:700;color:rgba(255,255,255,.8);-webkit-text-fill-color:rgba(255,255,255,.8);">di Seluruh Indonesia</span>
                </h1>

                <p class="hfu" style="animation-delay:.2s;color:rgba(186,230,253,.85);font-size:1.05rem;line-height:1.75;margin-bottom:32px;max-width:520px;">
                    Hotel, villa, resort, kosan &amp; kontrakan — harga terbaik, bayar aman via payment gateway, tiket langsung ke email Anda.
                </p>

                <!-- Trust badges -->
                <div class="hfu" style="animation-delay:.3s;display:flex;flex-wrap:wrap;gap:12px;margin-bottom:36px;">
                    @foreach([['✅','Booking Terjamin'],['🔒','Bayar Aman'],['📧','Tiket Instan'],['⭐','Rating 4.8/5']] as $badge)
                    <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);color:white;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;backdrop-filter:blur(6px);">
                        {{ $badge[0] }} {{ $badge[1] }}
                    </span>
                    @endforeach
                </div>

                <!-- CTA buttons -->
                <div class="hfu" style="animation-delay:.35s;display:flex;flex-wrap:wrap;gap:12px;">
                    <a href="{{ route('explore.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#1d4ed8,#0284c7);color:white;font-weight:700;font-size:15px;padding:14px 28px;border-radius:12px;text-decoration:none;box-shadow:0 6px 24px rgba(29,78,216,.45);transition:all .2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 30px rgba(29,78,216,.5)'" onmouseout="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(29,78,216,.45)'">
                        <i class="fas fa-compass"></i> Jelajahi Properti
                    </a>
                    @guest
                    <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.35);color:white;font-weight:700;font-size:15px;padding:14px 28px;border-radius:12px;text-decoration:none;backdrop-filter:blur(8px);transition:all .2s;" onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
                        <i class="fas fa-user-plus"></i> Daftar Gratis
                    </a>
                    @endguest
                </div>
            </div>

        </div>

        <!-- Search bar floating -->
        <div class="hfu" style="animation-delay:.45s;margin-top:44px;">
            <div style="background:rgba(255,255,255,.97);backdrop-filter:blur(20px);border-radius:18px;padding:20px 24px;box-shadow:0 24px 64px rgba(0,0,0,.25),0 0 0 1px rgba(255,255,255,.08);">
                <form action="{{ route('explore.index') }}" method="GET">
                    <div style="display:grid;grid-template-columns:1fr 1fr 160px 140px;gap:12px;align-items:end;" class="search-grid">
                        <div>
                            <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">📍 Kota / Properti</label>
                            <div style="position:relative;">
                                <input type="text" name="q" placeholder="Bali, Jakarta, Bandung..." class="form-input" style="padding-left:36px;border-radius:10px;">
                                <i class="fas fa-search" style="position:absolute;left:12px;top:11px;color:#94a3b8;font-size:13px;"></i>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">🏨 Tipe Properti</label>
                            <select name="category" class="form-select" style="border-radius:10px;">
                                <option value="">Semua Tipe</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">💰 Budget Maks</label>
                            <select name="max_price" class="form-select" style="border-radius:10px;">
                                <option value="">Semua Harga</option>
                                <option value="200000">≤ Rp 200rb</option>
                                <option value="500000">≤ Rp 500rb</option>
                                <option value="1000000">≤ Rp 1jt</option>
                                <option value="2000000">≤ Rp 2jt</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" style="width:100%;background:linear-gradient(135deg,#1d4ed8,#0ea5e9);color:white;font-weight:700;font-size:14px;padding:11px;border:none;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 4px 16px rgba(29,78,216,.4);transition:all .2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Quick tags -->
                <div style="margin-top:12px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="font-size:11px;color:#94a3b8;font-weight:600;">Populer:</span>
                    @foreach([['Bali','bali'],['Jakarta','jakarta'],['Bandung','bandung'],['Yogyakarta','yogyakarta'],['Lombok','lombok']] as $tag)
                    <a href="{{ route('explore.index', ['q'=>$tag[1]]) }}" style="font-size:12px;background:#f1f5f9;color:#475569;padding:4px 12px;border-radius:20px;text-decoration:none;font-weight:500;transition:all .15s;" onmouseover="this.style.background='#dbeafe';this.style.color='#1d4ed8'" onmouseout="this.style.background='#f1f5f9';this.style.color='#475569'">{{ $tag[0] }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Stats row -->
        <div class="hfu" style="animation-delay:.55s;margin-top:32px;display:flex;justify-content:center;flex-wrap:wrap;gap:0;">
            @foreach([[$stats['properties'].'+ Properti','fas fa-building'],[$stats['cities'].'+ Kota','fas fa-map-marker-alt'],[$stats['bookings'].'+ Tamu Puas','fas fa-smile']] as $stat)
            <div style="text-align:center;padding:0 24px;border-right:1px solid rgba(255,255,255,.12);last-child:border-0;">
                <i class="{{ $stat[1] }}" style="color:#7dd3fc;font-size:14px;margin-bottom:4px;display:block;"></i>
                <div style="font-size:1.25rem;font-weight:900;color:white;">{{ $stat[0] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Scroll hint -->
    <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);z-index:3;animation:bounce-y 2s infinite;">
        <i class="fas fa-chevron-down" style="color:rgba(255,255,255,.5);font-size:18px;"></i>
    </div>

    <!-- BG slider dots -->
    <div style="position:absolute;bottom:20px;right:24px;z-index:3;display:flex;gap:6px;" id="bg-dots">
        @foreach($heroBgs as $i => $bg)
        <button onclick="heroBgGo({{ $i }})" class="bg-dot" style="width:{{ $i===0?24:8 }}px;height:8px;border-radius:4px;background:{{ $i===0?'white':'rgba(255,255,255,.4)' }};border:none;cursor:pointer;transition:all .3s;padding:0;"></button>
        @endforeach
    </div>
</section>

<!-- ============================================================
     KATEGORI ICON STRIP
     ============================================================ -->
<section style="background:white;padding:48px 16px 40px;">
    <div class="max-w-6xl mx-auto">
        <div style="text-align:center;margin-bottom:32px;">
            <h2 class="section-title">Jelajahi Berdasarkan Tipe</h2>
            <p class="section-subtitle">Pilih sesuai kebutuhan dan budget Anda</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;" class="cat-grid">
            @php
            $catIcons = ['hotel'=>'🏨','villa'=>'🏡','resort'=>'🌴','kosan'=>'🚪','kontrakan'=>'🏘️'];
            $catColors = ['hotel'=>'#dbeafe,#1d4ed8','villa'=>'#dcfce7,#16a34a','resort'=>'#fef3c7,#d97706','kosan'=>'#fce7f3,#be185d','kontrakan'=>'#ede9fe,#7c3aed'];
            @endphp
            @foreach($categories as $cat)
            @php [$bg,$fg] = explode(',', $catColors[$cat->slug] ?? '#f1f5f9,#475569'); @endphp
            <a href="{{ route('explore.index', ['category'=>$cat->slug]) }}" style="display:flex;flex-direction:column;align-items:center;padding:24px 12px;border-radius:20px;border:2px solid transparent;transition:all .25s;text-decoration:none;background:{{ $bg }}20;" onmouseover="this.style.borderColor='{{ $fg }}';this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'" onmouseout="this.style.borderColor='transparent';this.style.transform='';this.style.boxShadow=''">
                <div style="width:60px;height:60px;border-radius:18px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:10px;">{{ $catIcons[$cat->slug] ?? '🏠' }}</div>
                <span style="font-weight:700;font-size:14px;color:#1e293b;">{{ $cat->name }}</span>
                <span style="font-size:12px;color:#94a3b8;margin-top:3px;">{{ $cat->properties_count }} properti</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURED — TOP RATED
     ============================================================ -->
<section style="background:#f8fafc;padding:56px 16px;">
    <div class="max-w-6xl mx-auto">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:12px;">
            <div>
                <span style="font-size:12px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:1px;">⭐ Pilihan Terbaik</span>
                <h2 class="section-title" style="margin-top:4px;">Properti Paling Populer</h2>
                <p class="section-subtitle">Rating tertinggi dari ribuan ulasan tamu nyata</p>
            </div>
            <a href="{{ route('explore.index', ['sort'=>'rating']) }}" class="btn-secondary btn-sm">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="prop-grid">
            @foreach($featured as $p)
            <a href="{{ route('explore.show', $p->slug) }}" style="display:block;text-decoration:none;border-radius:20px;overflow:hidden;background:white;box-shadow:0 2px 12px rgba(0,0,0,.07);transition:all .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(29,78,216,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'">
                <div style="position:relative;height:210px;overflow:hidden;">
                    <img src="{{ $p->getMainImageUrl() }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;transition:transform .4s;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                    <span style="position:absolute;top:12px;left:12px;background:white;color:#1d4ed8;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;box-shadow:0 2px 8px rgba(0,0,0,.12);">{{ $p->category->name }}</span>
                    <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,.5);color:#fbbf24;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px;display:flex;align-items:center;gap:4px;backdrop-filter:blur(4px);">
                        <i class="fas fa-star" style="font-size:10px;"></i> {{ number_format($p->rating_avg,1) }}
                    </div>
                </div>
                <div style="padding:18px;">
                    <p style="font-weight:700;font-size:15px;color:#1e293b;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->name }}</p>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:10px;display:flex;align-items:center;gap:4px;"><i class="fas fa-location-dot" style="color:#1d4ed8;"></i>{{ $p->city }}, {{ $p->province }}</p>
                    @php $facs = is_array($p->facilities) ? array_slice($p->facilities,0,3) : []; @endphp
                    @if($facs)
                    <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:12px;">
                        @foreach($facs as $f)<span style="font-size:11px;background:#eff6ff;color:#3b82f6;padding:2px 8px;border-radius:6px;">{{ $f }}</span>@endforeach
                    </div>
                    @endif
                    <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f1f5f9;padding-top:12px;">
                        <div>
                            <span style="font-weight:800;font-size:16px;color:#1d4ed8;">{{ $p->formatted_price }}</span>
                            <span style="font-size:12px;color:#94a3b8;">/malam</span>
                        </div>
                        <span style="font-size:12px;color:#64748b;"><i class="fas fa-star" style="color:#fbbf24;"></i> ({{ $p->rating_count }} ulasan)</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     PROMO BANNER
     ============================================================ -->
<section style="padding:48px 16px;background:white;">
    <div class="max-w-6xl mx-auto">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="promo-grid">

            <!-- Promo 1 — Big banner -->
            <div style="position:relative;border-radius:24px;overflow:hidden;min-height:240px;background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#0284c7);">
                <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=700&q=75" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.25;">
                <div style="position:relative;z-index:1;padding:32px;">
                    <span style="background:#fbbf24;color:#1e293b;font-size:11px;font-weight:800;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.5px;">🔥 Promo Eksklusif</span>
                    <h3 style="color:white;font-size:1.6rem;font-weight:900;margin:12px 0 8px;line-height:1.2;">Diskon s/d 30%<br>Hotel Berbintang</h3>
                    <p style="color:rgba(186,230,253,.85);font-size:13px;margin-bottom:20px;">Pesan sekarang untuk check-in bulan ini. Tersedia di 50+ hotel premium.</p>
                    <a href="{{ route('explore.index', ['category'=>'hotel','sort'=>'rating']) }}" style="display:inline-flex;align-items:center;gap:6px;background:white;color:#1d4ed8;font-weight:700;font-size:13px;padding:10px 20px;border-radius:10px;text-decoration:none;">
                        Lihat Hotel <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Promo 2 & 3 stacked -->
            <div style="display:flex;flex-direction:column;gap:20px;">
                <div style="position:relative;border-radius:20px;overflow:hidden;flex:1;min-height:108px;background:linear-gradient(135deg,#065f46,#059669);">
                    <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=70" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.2;">
                    <div style="position:relative;z-index:1;padding:22px 24px;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <span style="background:#34d399;color:#064e3b;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;">🏡 Villa Deal</span>
                            <h3 style="color:white;font-size:1.15rem;font-weight:800;margin:6px 0 4px;">Villa Bali mulai<br><span style="font-size:1.4rem;">Rp 750rb</span>/malam</h3>
                        </div>
                        <a href="{{ route('explore.index', ['category'=>'villa']) }}" style="background:white;color:#059669;font-weight:700;font-size:12px;padding:8px 16px;border-radius:8px;text-decoration:none;white-space:nowrap;">Lihat →</a>
                    </div>
                </div>

                <div style="position:relative;border-radius:20px;overflow:hidden;flex:1;min-height:108px;background:linear-gradient(135deg,#7c2d12,#ea580c);">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=70" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.2;">
                    <div style="position:relative;z-index:1;padding:22px 24px;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <span style="background:#fb923c;color:#431407;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;">⚡ Flash Sale</span>
                            <h3 style="color:white;font-size:1.15rem;font-weight:800;margin:6px 0 4px;">Resort &amp; Kosan<br><span style="font-size:1.4rem;">Harga Terbaik</span></h3>
                        </div>
                        <a href="{{ route('explore.index') }}" style="background:white;color:#ea580c;font-weight:700;font-size:12px;padding:8px 16px;border-radius:8px;text-decoration:none;white-space:nowrap;">Lihat →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     HOW IT WORKS
     ============================================================ -->
<section style="background:#eff6ff;padding:56px 16px;">
    <div class="max-w-5xl mx-auto text-center">
        <span style="font-size:12px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:1px;">Cara Kerja</span>
        <h2 class="section-title" style="margin-top:6px;margin-bottom:8px;">Booking dalam 3 Langkah</h2>
        <p class="section-subtitle" style="margin-bottom:44px;">Mudah, cepat, dan aman</p>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px;position:relative;" class="steps-grid">
            <!-- connector line -->
            <div style="position:absolute;top:36px;left:calc(16.6% + 8px);right:calc(16.6% + 8px);height:2px;background:linear-gradient(90deg,#1d4ed8,#0ea5e9);opacity:.25;" class="hide-mobile"></div>

            @php
            $steps = [
                ['1','🔍','Pilih Properti','Jelajahi ribuan pilihan dan filter sesuai budget, lokasi, dan fasilitas yang Anda butuhkan.','#dbeafe','#1d4ed8'],
                ['2','💳','Booking & Bayar','Isi data tamu, pilih tanggal, dan bayar via payment gateway otomatis atau transfer bank.','#dcfce7','#16a34a'],
                ['3','🎫','Terima Tiket','Tiket PDF langsung dikirim ke email. Tunjukkan saat check-in dan nikmati perjalanan!','#fef3c7','#d97706'],
            ];
            @endphp
            @foreach($steps as $step)
            <div>
                <div style="width:72px;height:72px;border-radius:22px;background:{{ $step[4] }};display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 16px;position:relative;z-index:1;box-shadow:0 4px 16px rgba(0,0,0,.08);">{{ $step[1] }}</div>
                <div style="width:24px;height:24px;border-radius:50%;background:{{ $step[5] }};color:white;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;margin:-14px auto 14px;border:3px solid white;">{{ $step[0] }}</div>
                <h3 style="font-size:16px;font-weight:800;color:#1e293b;margin-bottom:8px;">{{ $step[2] }}</h3>
                <p style="font-size:13px;color:#64748b;line-height:1.6;">{{ $step[3] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONI STATIC
     ============================================================ -->
<section style="background:white;padding:56px 16px;">
    <div class="max-w-6xl mx-auto">
        <div style="text-align:center;margin-bottom:40px;">
            <span style="font-size:12px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:1px;">💬 Testimoni</span>
            <h2 class="section-title" style="margin-top:6px;">Kata Mereka</h2>
            <p class="section-subtitle">Ulasan nyata dari tamu yang sudah menikmati layanan NginepYuk</p>
        </div>

        @php
        $testimonials_static = [
            ['name'=>'Rina Andriani','city'=>'Jakarta','avatar'=>'R','rating'=>5,'text'=>'Booking sangat mudah dan cepat! Tiket PDF langsung masuk email dalam hitungan menit. Kamar hotelnya persis seperti foto, pelayanannya juga memuaskan. Pasti pakai NginepYuk lagi!','prop'=>'Hotel Bintang Lima Jakarta','date'=>'Maret 2026'],
            ['name'=>'Budi Santoso','city'=>'Bandung','avatar'=>'B','rating'=>5,'text'=>'Saya sudah coba beberapa platform booking, tapi NginepYuk yang paling simple prosesnya. Bayar lewat GoPay langsung confirmed, nggak perlu nunggu lama. Villa Balinya juga amazing banget!','prop'=>'Villa Sunset Bali','date'=>'Februari 2026'],
            ['name'=>'Sari Dewi','city'=>'Surabaya','avatar'=>'S','rating'=>5,'text'=>'Harga transparan, nggak ada biaya tersembunyi. Admin juga fast response di WhatsApp. Recommend banget buat yang mau cari penginapan murah tapi nyaman!','prop'=>'Kosan Premium Depok','date'=>'Maret 2026'],
            ['name'=>'Dian Prasetyo','city'=>'Yogyakarta','avatar'=>'D','rating'=>5,'text'=>'Resort di Bandungnya bagus banget, sesuai ekspektasi. Proses booking cuma 5 menit, bayar transfer langsung dikonfirmasi admin dalam 1 jam. Top!','prop'=>'Resort Alam Bandung','date'=>'Januari 2026'],
            ['name'=>'Maya Kusuma','city'=>'Bali','avatar'=>'M','rating'=>5,'text'=>'Pertama kali pakai NginepYuk buat honeymoon ke Bali. Surprised banget sama kualitas villaanya, private pool beneran keren. Tiket PDF-nya juga rapi dan profesional.','prop'=>'Villa Sunset Bali','date'=>'Februari 2026'],
            ['name'=>'Rizky Ramadhan','city'=>'Makassar','avatar'=>'R','rating'=>4,'text'=>'Fitur pembayarannya lengkap, bisa QRIS, transfer, kartu kredit. Yang paling suka sistem tiket digitalnya, praktis tinggal tunjukin HP pas check-in.','prop'=>'Hotel Grand Yogyakarta','date'=>'Maret 2026'],
        ];
        @endphp

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;" class="testi-grid">
            @foreach($testimonials_static as $t)
            <div style="background:#f8fafc;border-radius:18px;padding:22px;border:1px solid #f1f5f9;transition:all .2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(29,78,216,.1)';this.style.borderColor='#bfdbfe'" onmouseout="this.style.boxShadow='';this.style.borderColor='#f1f5f9'">
                <!-- Stars -->
                <div style="display:flex;gap:2px;margin-bottom:12px;">
                    @for($i=0;$i<$t['rating'];$i++)<i class="fas fa-star" style="color:#fbbf24;font-size:13px;"></i>@endfor
                </div>
                <p style="font-size:13px;color:#475569;line-height:1.7;margin-bottom:16px;">"{{ $t['text'] }}"</p>
                <div style="display:flex;align-items:center;gap:10px;border-top:1px solid #e2e8f0;padding-top:14px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#1d4ed8,#0ea5e9);color:white;font-weight:800;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $t['avatar'] }}</div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:700;font-size:13px;color:#1e293b;">{{ $t['name'] }}</p>
                        <p style="font-size:11px;color:#94a3b8;">{{ $t['prop'] }} · {{ $t['date'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Rating summary -->
        <div style="margin-top:36px;background:linear-gradient(135deg,#eff6ff,#f0fdf4);border-radius:20px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div>
                <div style="display:flex;align-items:baseline;gap:8px;">
                    <span style="font-size:3rem;font-weight:900;color:#1d4ed8;line-height:1;">4.9</span>
                    <div>
                        <div style="display:flex;gap:2px;">@for($i=0;$i<5;$i++)<i class="fas fa-star" style="color:#fbbf24;font-size:16px;"></i>@endfor</div>
                        <p style="font-size:12px;color:#64748b;margin-top:2px;">dari 2.400+ ulasan</p>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:32px;flex-wrap:wrap;">
                @foreach([['98%','Puas dengan layanan'],['96%','Booking mudah'],['99%','Rekomendasi teman']] as $r)
                <div style="text-align:center;">
                    <div style="font-size:1.5rem;font-weight:900;color:#1d4ed8;">{{ $r[0] }}</div>
                    <div style="font-size:12px;color:#64748b;">{{ $r[1] }}</div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('explore.index') }}" class="btn-primary btn-sm">Mulai Booking Sekarang</a>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA BOTTOM
     ============================================================ -->
<section style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 40%,#1d4ed8 70%,#0ea5e9 100%);position:relative;overflow:hidden;margin-bottom:0;">
    <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;background:radial-gradient(circle,rgba(99,102,241,.3),transparent 70%);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;left:-60px;width:260px;height:260px;background:radial-gradient(circle,rgba(14,165,233,.25),transparent 70%);border-radius:50%;pointer-events:none;"></div>
    <div class="max-w-3xl mx-auto px-4 py-20 text-center" style="position:relative;z-index:1;">
        <div style="display:inline-flex;align-items:center;gap:8px;padding:7px 18px;border-radius:50px;background:rgba(255,255,255,.12);color:#bfdbfe;font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,.2);margin-bottom:20px;">
            ✨ Bergabung dengan 10.000+ tamu puas
        </div>
        <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:white;line-height:1.2;margin-bottom:16px;">
            Siap Memulai<br>
            <span style="background:linear-gradient(90deg,#93c5fd,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Perjalanan Impian?</span>
        </h2>
        <p style="color:#bfdbfe;font-size:1.05rem;margin-bottom:32px;line-height:1.7;">Daftar gratis dan akses ribuan properti terbaik di seluruh Indonesia.</p>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;">
            @guest
            <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:white;color:#1d4ed8;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;text-decoration:none;box-shadow:0 4px 20px rgba(0,0,0,.2);transition:all .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                <i class="fas fa-user-plus"></i> Daftar Gratis Sekarang
            </a>
            <a href="{{ route('explore.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);color:white;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;text-decoration:none;transition:all .2s;" onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
                <i class="fas fa-compass"></i> Jelajahi Properti
            </a>
            @else
            <a href="{{ route('explore.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:white;color:#1d4ed8;font-weight:700;padding:14px 32px;border-radius:12px;font-size:1rem;text-decoration:none;box-shadow:0 4px 20px rgba(0,0,0,.2);transition:all .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                <i class="fas fa-compass"></i> Jelajahi Sekarang
            </a>
            @endguest
        </div>
    </div>
</section>

<style>
.hfu { opacity:0; transform:translateY(22px); animation:hfadeup .7s ease forwards; }
@keyframes hfadeup { to { opacity:1; transform:translateY(0); } }
@keyframes pdot { 0%,100%{box-shadow:0 0 8px #34d399;opacity:1}50%{box-shadow:0 0 16px #34d399;opacity:.5} }
@keyframes hero-zoom { 0%{transform:scale(1.05)} 100%{transform:scale(1.0)} }
@keyframes bounce-y { 0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(6px)} }

/* Responsive */
@media(max-width:768px){
    .search-grid { grid-template-columns:1fr !important; }
    .cat-grid    { grid-template-columns:repeat(3,1fr) !important; }
    .prop-grid   { grid-template-columns:1fr !important; }
    .promo-grid  { grid-template-columns:1fr !important; }
    .steps-grid  { grid-template-columns:1fr !important; }
    .testi-grid  { grid-template-columns:1fr !important; }
}
@media(max-width:480px){
    .cat-grid { grid-template-columns:repeat(2,1fr) !important; }
}
</style>

<script>
// Hero BG slider
let bgIdx = 0;
const bgSlides = document.querySelectorAll('.hero-bg-slide');
const bgDots   = document.querySelectorAll('.bg-dot');
const bgTotal  = bgSlides.length;

function heroBgGo(idx) {
    bgSlides[bgIdx].style.opacity = 0;
    bgDots[bgIdx].style.width = '8px';
    bgDots[bgIdx].style.background = 'rgba(255,255,255,.4)';
    bgIdx = (idx + bgTotal) % bgTotal;
    bgSlides[bgIdx].style.opacity = 1;
    bgDots[bgIdx].style.width = '24px';
    bgDots[bgIdx].style.background = 'white';
}
setInterval(() => heroBgGo(bgIdx + 1), 5000);

// Smooth scroll on arrow click
document.querySelector('#hero .fa-chevron-down')?.closest('div')?.addEventListener('click', () => {
    document.querySelector('#hero')?.nextElementSibling?.scrollIntoView({ behavior: 'smooth' });
});
</script>

@endsection

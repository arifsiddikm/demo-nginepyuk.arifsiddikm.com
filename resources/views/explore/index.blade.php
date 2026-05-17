@extends('layouts.app')
@section('title', 'Jelajahi Properti')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="section-title">Jelajahi Properti</h1>
        <p class="section-subtitle">{{ $properties->total() }} properti ditemukan</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- SIDEBAR FILTER -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="card p-5 sticky top-20">
                <h3 class="font-bold text-slate-700 mb-4 flex items-center gap-2"><i class="fas fa-sliders text-blue-600"></i> Filter</h3>
                <form action="{{ route('explore.index') }}" method="GET" id="filter-form">
                    <div class="form-group">
                        <label class="form-label">Cari</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Nama / kota...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kota</label>
                        <select name="city" class="form-select">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Min (Rp)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-input" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Max (Rp)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-input" placeholder="10.000.000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Terbaik</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">
                        <i class="fas fa-search"></i> Terapkan Filter
                    </button>
                    @if(request()->hasAny(['q','category','city','min_price','max_price','sort']))
                        <a href="{{ route('explore.index') }}" class="btn-secondary w-full justify-center mt-2 text-sm">
                            <i class="fas fa-xmark"></i> Reset Filter
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1">
            @if($properties->count() === 0)
                <div class="card p-16 text-center">
                    <i class="fas fa-magnifying-glass text-5xl text-slate-200 mb-4"></i>
                    <h3 class="font-bold text-slate-600 text-xl mb-2">Properti Tidak Ditemukan</h3>
                    <p class="text-slate-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <a href="{{ route('explore.index') }}" class="btn-primary mt-4 inline-flex">Reset & Tampilkan Semua</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($properties as $p)
                    <a href="{{ route('explore.show', $p->slug) }}" class="card card-hover property-card block group">
                        <div class="overflow-hidden relative h-[200px]">
                            <img src="{{ $p->getMainImageUrl() }}" alt="{{ $p->name }}" class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                            <span class="absolute top-3 left-3 bg-white text-blue-700 text-xs font-bold px-3 py-1 rounded-full shadow">{{ $p->category->name }}</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-sm mb-1 line-clamp-1">{{ $p->name }}</h3>
                            <div class="flex items-center gap-1 text-xs text-slate-400 mb-3">
                                <i class="fas fa-location-dot text-blue-400"></i>
                                {{ $p->city }}, {{ $p->province }}
                            </div>
                            <div class="flex flex-wrap gap-1 mb-3">
                                @php $facs = is_array($p->facilities) ? $p->facilities : []; @endphp
                                @if(count($facs) > 0)
                                    @foreach(array_slice($facs, 0, 3) as $fac)
                                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded">{{ $fac }}</span>
                                    @endforeach
                                    @if(count($facs) > 3)
                                        <span class="text-xs text-slate-400">+{{ count($facs) - 3 }} lainnya</span>
                                    @endif
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-blue-700 font-bold">{{ $p->formatted_price }}</span>
                                    <span class="text-slate-400 text-xs">/malam</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <span class="text-xs font-semibold">{{ number_format($p->rating_avg, 1) }}</span>
                                    <span class="text-xs text-slate-400">({{ $p->rating_count }})</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="mt-8 flex justify-center gap-1">
                    @if($properties->onFirstPage())
                        <span class="pagination-link opacity-40 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                    @else
                        <a href="{{ $properties->previousPageUrl() }}" class="pagination-link"><i class="fas fa-chevron-left text-xs"></i></a>
                    @endif

                    @foreach($properties->getUrlRange(max(1, $properties->currentPage()-2), min($properties->lastPage(), $properties->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="pagination-link {{ $page == $properties->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($properties->hasMorePages())
                        <a href="{{ $properties->nextPageUrl() }}" class="pagination-link"><i class="fas fa-chevron-right text-xs"></i></a>
                    @else
                        <span class="pagination-link opacity-40 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

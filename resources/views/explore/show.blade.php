@extends('layouts.app')
@section('title', $property->name)
@section('meta_description', Str::limit(strip_tags($property->description), 160))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- Breadcrumb -->
    <nav class="text-sm text-slate-400 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('explore.index') }}" class="hover:text-blue-600">Jelajahi</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-600">{{ $property->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">

            <!-- IMAGE SLIDER -->
            @php $allImages = $property->getAllImages(); @endphp
            <div class="rounded-2xl overflow-hidden mb-4 relative" id="slider-container" style="height:400px;background:#f1f5f9;">
                @if(count($allImages) > 0)
                    @foreach($allImages as $i => $imgUrl)
                    <div class="slider-slide absolute inset-0 transition-opacity duration-500 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                        <img src="{{ $imgUrl }}" alt="{{ $property->name }} - foto {{ $i+1 }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80'">
                    </div>
                    @endforeach
                    @if(count($allImages) > 1)
                    <!-- Prev/Next arrows -->
                    <button onclick="sliderPrev()" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="sliderNext()" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <!-- Dots -->
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                        @foreach($allImages as $i => $url)
                        <button onclick="sliderGo({{ $i }})" class="slider-dot w-2 h-2 rounded-full transition {{ $i===0 ? 'bg-white' : 'bg-white/50' }}"></button>
                        @endforeach
                    </div>
                    <!-- Counter -->
                    <div class="absolute top-3 right-3 z-20 bg-black/50 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                        <span id="slider-counter">1</span>/{{ count($allImages) }}
                    </div>
                    @endif
                @else
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80"
                         class="w-full h-full object-cover">
                @endif
            </div>

            <!-- Thumbnail strip -->
            @if(count($allImages) > 1)
            <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
                @foreach($allImages as $i => $imgUrl)
                <button onclick="sliderGo({{ $i }})"
                    class="slider-thumb flex-shrink-0 w-20 h-16 rounded-xl overflow-hidden border-2 transition {{ $i===0 ? 'border-blue-600' : 'border-transparent opacity-70 hover:opacity-100' }}">
                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=60'">
                </button>
                @endforeach
            </div>
            @endif

            <!-- Info Card -->
            <div class="card p-6 mb-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-3 py-1 rounded-full">{{ $property->category->name }}</span>
                        <h1 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $property->name }}</h1>
                        <p class="text-slate-500 flex items-center gap-2 mt-1 text-sm">
                            <i class="fas fa-location-dot text-blue-500"></i>
                            {{ $property->address }}, {{ $property->city }}, {{ $property->province }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-1 justify-end mb-1">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star {{ $i<=floor($property->rating_avg) ? 'text-yellow-400' : 'text-slate-200' }} text-sm"></i>
                            @endfor
                            <span class="font-bold text-slate-700 ml-1">{{ number_format($property->rating_avg,1) }}</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $property->rating_count }} ulasan</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-slate-600 py-4 border-y border-slate-100 mb-5">
                    <span class="flex items-center gap-2"><i class="fas fa-bed text-blue-400"></i> {{ $property->total_rooms }} kamar total</span>
                    <span class="flex items-center gap-2"><i class="fas fa-users text-blue-400"></i> Maks. {{ $property->max_guests }} tamu/kamar</span>
                    <span class="flex items-center gap-2"><i class="fas fa-tag text-blue-400"></i> {{ $property->formatted_price }}/malam</span>
                </div>

                <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed mb-6">
                    {!! $property->description !!}
                </div>

                <!-- Fasilitas -->
                @php $facs = is_array($property->facilities) ? $property->facilities : []; @endphp
                @if(count($facs) > 0)
                <div>
                    <h3 class="font-bold text-slate-700 mb-3">Fasilitas</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($facs as $fac)
                            <span class="flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-xl text-sm font-medium">
                                <i class="fas fa-check text-xs"></i> {{ $fac }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Testimoni -->
            @if($property->testimonials->count() > 0)
            <div class="card p-6">
                <h3 class="font-bold text-slate-700 text-lg mb-5">Ulasan Tamu</h3>
                <div class="space-y-5">
                    @foreach($property->testimonials->take(5) as $t)
                    <div class="flex gap-4 pb-5 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($t->user->name,0,1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-sm">{{ $t->user->name }}</span>
                                <span class="text-xs text-slate-400">{{ $t->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex gap-0.5 mb-2">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star text-xs {{ $i<=$t->rating ? 'text-yellow-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            <p class="text-slate-600 text-sm">{{ $t->review }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT: Booking Form -->
        <div class="lg:col-span-1">
            <div class="card p-6 sticky top-20">
                <div class="mb-4">
                    <span class="text-blue-700 font-extrabold text-2xl">{{ $property->formatted_price }}</span>
                    <span class="text-slate-400 text-sm">/malam</span>
                </div>
                <form action="{{ route('booking.checkout', $property->slug) }}" method="GET" id="booking-form">
                    <div class="form-group">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="checkin" id="checkin" class="form-input"
                            value="{{ old('checkin', now()->addDay()->format('Y-m-d')) }}"
                            min="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="checkout" id="checkout" class="form-input"
                            value="{{ old('checkout', now()->addDays(2)->format('Y-m-d')) }}"
                            min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Kamar</label>
                        <select name="rooms" class="form-select" id="rooms-select">
                            @for($i=1;$i<=min(10,$property->total_rooms);$i++)
                                <option value="{{ $i }}">{{ $i }} Kamar</option>
                            @endfor
                        </select>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 mb-4">
                        <div class="text-xs text-slate-500 mb-1" id="price-detail">-</div>
                        <div class="flex justify-between text-xs text-slate-500 mb-1">
                            <span>PPN 11%</span><span id="tax-val">-</span>
                        </div>
                        <div class="flex justify-between font-bold text-slate-800 border-t border-blue-200 pt-2 mt-2">
                            <span class="text-sm">Total Estimasi</span>
                            <span class="text-blue-700 text-sm" id="total-val">-</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center py-3">
                        <i class="fas fa-calendar-check"></i> Booking Sekarang
                    </button>
                </form>
                <div class="border-t border-slate-100 mt-4 pt-4">
                    <a href="https://wa.me/6289514392694?text=Halo, saya tertarik dengan {{ urlencode($property->name) }}"
                       target="_blank" class="btn-success w-full justify-center text-sm">
                        <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related -->
    @if($related->count() > 0)
    <div class="mt-16">
        <h2 class="section-title mb-6">Properti Serupa</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $p)
            <a href="{{ route('explore.show', $p->slug) }}" class="card card-hover property-card block">
                <div class="overflow-hidden h-[180px]">
                    <img src="{{ $p->getMainImageUrl() }}" alt="{{ $p->name }}" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-sm text-slate-800 mb-1">{{ $p->name }}</h3>
                    <p class="text-xs text-slate-400 mb-2">{{ $p->city }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-700 font-bold text-sm">{{ $p->formatted_price }}/malam</span>
                        <div class="flex items-center gap-1"><i class="fas fa-star text-yellow-400 text-xs"></i><span class="text-xs">{{ number_format($p->rating_avg,1) }}</span></div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
const pricePerNight = {{ $property->price_per_night }};
let sliderIndex = 0;
const slides = document.querySelectorAll('.slider-slide');
const dots   = document.querySelectorAll('.slider-dot');
const thumbs = document.querySelectorAll('.slider-thumb');
const counter = document.getElementById('slider-counter');
const total  = slides.length;

function sliderGo(idx) {
    slides[sliderIndex].classList.replace('opacity-100','opacity-0');
    slides[sliderIndex].classList.replace('z-10','z-0');
    dots[sliderIndex]?.classList.replace('bg-white','bg-white/50');
    thumbs[sliderIndex]?.classList.remove('border-blue-600');
    thumbs[sliderIndex]?.classList.add('border-transparent','opacity-70');

    sliderIndex = (idx + total) % total;

    slides[sliderIndex].classList.replace('opacity-0','opacity-100');
    slides[sliderIndex].classList.replace('z-0','z-10');
    dots[sliderIndex]?.classList.replace('bg-white/50','bg-white');
    thumbs[sliderIndex]?.classList.add('border-blue-600');
    thumbs[sliderIndex]?.classList.remove('border-transparent','opacity-70');
    if (counter) counter.textContent = sliderIndex + 1;
}
function sliderNext() { sliderGo(sliderIndex + 1); }
function sliderPrev() { sliderGo(sliderIndex - 1); }

// Auto-slide every 5s
if (total > 1) setInterval(sliderNext, 5000);

// Price calculator
function updatePrice() {
    const checkin  = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    const rooms    = parseInt(document.getElementById('rooms-select').value) || 1;
    if (checkin && checkout && checkin < checkout) {
        const nights   = Math.round((new Date(checkout) - new Date(checkin)) / 86400000);
        const subtotal = pricePerNight * nights * rooms;
        const tax      = subtotal * 0.11;
        const total    = subtotal + tax;
        document.getElementById('price-detail').textContent =
            'Rp ' + pricePerNight.toLocaleString('id') + ' × ' + nights + ' malam × ' + rooms + ' kamar';
        document.getElementById('tax-val').textContent   = 'Rp ' + Math.round(tax).toLocaleString('id');
        document.getElementById('total-val').textContent = 'Rp ' + Math.round(total).toLocaleString('id');
    }
}
['checkin','checkout','rooms-select'].forEach(id =>
    document.getElementById(id)?.addEventListener('change', updatePrice)
);
updatePrice();
</script>
@endpush
@endsection

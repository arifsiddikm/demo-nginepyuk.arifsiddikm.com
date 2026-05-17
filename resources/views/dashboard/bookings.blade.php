@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-col md:flex-row gap-6">
        <aside class="w-full md:w-52 flex-shrink-0">
            <div class="card overflow-hidden">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 transition"><i class="fas fa-gauge w-4"></i> Dashboard</a>
                <a href="{{ route('dashboard.bookings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium bg-blue-600 text-white transition border-t border-slate-100"><i class="fas fa-bookmark w-4"></i> Pesanan Saya</a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 transition border-t border-slate-100"><i class="fas fa-user w-4"></i> Profil</a>
            </div>
        </aside>
        <div class="flex-1">
            <h1 class="font-bold text-xl text-slate-800 mb-5">Pesanan Saya</h1>
            @if($bookings->count() === 0)
                <div class="card p-12 text-center text-slate-400">
                    <i class="fas fa-bookmark text-5xl mb-3 text-slate-200"></i>
                    <p>Belum ada pesanan. <a href="{{ route('explore.index') }}" class="text-blue-600 underline">Mulai pesan</a> sekarang!</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($bookings as $b)
                    <div class="card p-5">
                        <div class="flex gap-4">
                            <img src="{{ $b->property->thumbnail_url }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $b->property->name }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $b->booking_code }}</p>
                                    </div>
                                    <span class="badge badge-{{ $b->status_color }} flex-shrink-0">{{ $b->status_label }}</span>
                                </div>
                                <div class="flex flex-wrap gap-4 mt-2 text-xs text-slate-500">
                                    <span><i class="fas fa-calendar text-blue-400 mr-1"></i>{{ $b->checkin_date->format('d M Y') }} – {{ $b->checkout_date->format('d M Y') }}</span>
                                    <span><i class="fas fa-moon text-blue-400 mr-1"></i>{{ $b->nights }} Malam</span>
                                    <span><i class="fas fa-bed text-blue-400 mr-1"></i>{{ $b->rooms }} Kamar</span>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="font-bold text-blue-700 text-sm">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</span>
                                    <div class="flex gap-2">
                                        @if(in_array($b->status, ['confirmed','completed']))
                                            <a href="{{ route('booking.ticket', $b->booking_code) }}" class="btn-success btn-sm"><i class="fas fa-download"></i> Tiket</a>
                                        @endif
                                        @if($b->status === 'completed' && !$b->testimonial)
                                            <button onclick="openTestimonialModal('{{ $b->booking_code }}')" class="btn-warning btn-sm"><i class="fas fa-star"></i> Ulasan</button>
                                        @endif
                                        @if(in_array($b->status,['pending','waiting_payment']))
                                            <a href="{{ route('booking.show', $b->booking_code) }}" class="btn-primary btn-sm"><i class="fas fa-credit-card"></i> Bayar</a>
                                        @else
                                            <a href="{{ route('booking.show', $b->booking_code) }}" class="btn-secondary btn-sm">Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="flex justify-center gap-1 mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Testimonial Modal -->
<div id="testimonial-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md mx-4 shadow-2xl">
        <h3 class="font-bold text-xl text-slate-800 mb-5">Tulis Ulasan</h3>
        <form id="testimonial-form" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Rating</label>
                <div class="flex gap-2" id="star-rating">
                    @for($i=1;$i<=5;$i++)
                    <button type="button" onclick="setRating({{ $i }})" data-val="{{ $i }}" class="text-3xl text-slate-200 hover:text-yellow-400 transition star-btn">
                        <i class="fas fa-star"></i>
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Ulasan <span class="text-red-500">*</span></label>
                <textarea name="review" class="form-textarea" placeholder="Bagaimana pengalaman menginap Anda?" required minlength="10"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeTestimonialModal()" class="btn-secondary flex-1 justify-center">Batal</button>
                <button type="submit" class="btn-primary flex-1 justify-center"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openTestimonialModal(code) {
    document.getElementById('testimonial-form').action = '/dashboard/testimoni/' + code;
    document.getElementById('testimonial-modal').classList.remove('hidden');
}
function closeTestimonialModal() {
    document.getElementById('testimonial-modal').classList.add('hidden');
}
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        btn.style.color = i < val ? '#f59e0b' : '';
    });
}
</script>
@endpush
@endsection

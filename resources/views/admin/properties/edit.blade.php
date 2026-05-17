@extends('layouts.admin')
@section('title', 'Edit Properti')
@section('page_title', 'Edit Properti')

@section('content')
<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    @if($errors->any())
    <div class="alert alert-error mb-5">
        <i class="fas fa-circle-exclamation"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-6">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Informasi Properti</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group mb-0 md:col-span-2">
                        <label class="form-label">Nama Properti <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $property->name) }}" class="form-input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id', $property->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $property->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $property->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group mb-0 md:col-span-2">
                        <label class="form-label">Alamat <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $property->address) }}" class="form-input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Kota <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $property->city) }}" class="form-input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Provinsi <span class="text-red-500">*</span></label>
                        <input type="text" name="province" value="{{ old('province', $property->province) }}" class="form-input" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Harga / Malam (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price_per_night" value="{{ old('price_per_night', $property->price_per_night) }}" class="form-input" min="1" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Total Kamar <span class="text-red-500">*</span></label>
                        <input type="number" name="total_rooms" value="{{ old('total_rooms', $property->total_rooms) }}" class="form-input" min="1" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Maks. Tamu</label>
                        <input type="number" name="max_guests" value="{{ old('max_guests', $property->max_guests) }}" class="form-input" min="1">
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Deskripsi</h3>
                <textarea name="description" id="description-editor" class="form-textarea" style="min-height:200px">{{ old('description', $property->description) }}</textarea>
            </div>

            <div class="card p-6">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Fasilitas</h3>
                @php
                $allFacilities = ['WiFi','AC','TV','Kolam Renang','Gym','Restoran','Parkir','Sarapan','Laundry','Dapur','Kamar Mandi Dalam','Private Pool','BBQ Area','Spa','Taman'];
                $selectedFacilities = old('facilities', $property->facilities ?? []);
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($allFacilities as $fac)
                    <label class="form-checkbox">
                        <input type="checkbox" name="facilities[]" value="{{ $fac }}" {{ in_array($fac, (array)$selectedFacilities) ? 'checked' : '' }}>
                        <label>{{ $fac }}</label>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-6">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Foto Utama (Thumbnail)</h3>
                @if($property->thumbnail)
                    <img src="{{ $property->thumbnail_url }}" class="w-full h-40 object-cover rounded-xl mb-3 border">
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
                <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah</p>
            </div>
            <div class="card p-6">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-3">Tambah Galeri Foto</h3>
                <input type="file" name="images[]" accept="image/*" multiple class="form-input">
            </div>
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full justify-center py-3">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary w-full justify-center mt-2">Batal</a>
            </div>
        </div>
    </div>
</form>

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
@endpush
@push('scripts')
<script type="importmap">{"imports":{"ckeditor5":"https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js","ckeditor5/":"https://cdn.ckeditor.com/ckeditor5/43.0.0/"}}</script>
<script type="module">
import { ClassicEditor, Essentials, Paragraph, Bold, Italic, Link, List, Heading, BlockQuote } from 'ckeditor5';
ClassicEditor.create(document.getElementById('description-editor'), {
    plugins: [Essentials, Paragraph, Bold, Italic, Link, List, Heading, BlockQuote],
    toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote']
}).catch(console.error);
</script>
@endpush
@endsection

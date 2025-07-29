@extends('layouts.admin')
@section('title', 'Tambah Testimoni Baru')

@section('content')
{{-- Dibungkus dengan .content-card untuk efek kaca --}}
<div class="content-card">
    <div class="mb-4">
        <h1 class="mb-0">Tambah Testimoni Baru</h1>
        <p class="text-white-50">Isi form di bawah ini untuk menambahkan testimoni secara manual.</p>
    </div>

    {{-- Tampilkan pesan error validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf

        {{-- Nama Pelanggan --}}
        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
        </div>

        {{-- Produk Terkait --}}
        <div class="mb-3">
            <label for="product_id" class="form-label">Untuk Produk</label>
            <select class="form-select" id="product_id" name="product_id" required>
                <option value="" disabled selected>-- Pilih Produk --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Isi Testimoni --}}
        <div class="mb-3">
            <label for="content" class="form-label">Isi Testimoni (Teks)</label>
            <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
        </div>

        {{-- URL Video (Opsional) --}}
        <div class="mb-3">
            <label for="video_url" class="form-label">URL Video (Opsional)</label>
            <input type="url" class="form-control" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxx...">
            <div class="form-text">Masukkan URL lengkap dari YouTube, Instagram, Twitter, atau TikTok.</div>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="approved" {{ old('status', 'approved') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                <option value="pending" {{ old('status', 'approved') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        
        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary-custom me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Testimoni</button>
        </div>
    </form>
</div>
@endsection
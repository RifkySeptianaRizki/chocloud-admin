@extends('layouts.admin')
@section('title', 'Tambah Testimoni Baru')

@section('content')
    <h1>Tambah Testimoni Baru</h1>
    <p>Isi form di bawah ini untuk menambahkan testimoni secara manual.</p>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf

                {{-- Nama Pelanggan --}}
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Produk Terkait --}}
                <div class="mb-3">
                    <label for="product_id" class="form-label">Untuk Produk</label>
                    <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                        <option value="" disabled selected>-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Isi Testimoni --}}
                <div class="mb-3">
                    <label for="content" class="form-label">Isi Testimoni (Teks)</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- URL Video (Opsional) --}}
                <div class="mb-3">
                    <label for="video_url" class="form-label">URL Video (Opsional)</label>
                    <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxx">
                    <div class="form-text">Masukkan URL lengkap dari YouTube, Instagram, Twitter, atau TikTok.</div>
                    @error('video_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="approved" {{ old('status', 'approved') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                        <option value="pending" {{ old('status', 'approved') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Testimoni</button>
            </form>
        </div>
    </div>
@endsection
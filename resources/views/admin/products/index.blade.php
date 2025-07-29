@extends('layouts.admin')
@section('title', 'Manajemen Produk')

@section('content')
<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Manajemen Produk</h1>
            <p class="text-white-50">Kelola semua produk yang tersedia di toko Anda.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-glass btn-sm mt-4">
            <i class="fas fa-plus me-2"></i>Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Mengganti Tabel dengan Grid Kartu Produk --}}
    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-4 col-md-6 mb-4">
            {{-- Ganti bagian ini di dalam file index.blade.php Anda --}}

<div class="product-card">
    <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" class="product-card-img-top" alt="{{ $product->name }}">
    
    {{-- 1. Card-body menjadi flex container vertikal --}}
    <div class="card-body d-flex flex-column">

        {{-- 2. Konten utama (nama, harga, kategori) dibungkus --}}
        {{--    Class 'flex-grow-1' membuatnya mengisi ruang kosong --}}
        <div class="text-center flex-grow-1">
            @if ($product->category)
                {{-- Sedikit ubah warna badge agar lebih menarik --}}
                <span class="badge mb-2" style="background-color: #581c87;">{{ $product->category->name }}</span>
            @endif
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>

        {{-- 3. Tombol aksi akan otomatis terdorong ke bawah --}}
        <div class="card-actions d-flex justify-content-end mt-3">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-action-edit me-2">Edit</a>
            <button class="btn btn-sm btn-action-delete btn-delete" 
                    data-url="{{ route('admin.products.destroy', $product->id) }}">
                Hapus
            </button>
        </div>

    </div>
</div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <p class="mb-0">Belum ada produk yang ditambahkan.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection


@push('scripts')
{{-- Script JavaScript untuk tombol hapus tidak diubah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.body.addEventListener('click', async function(e) {
        if (e.target && e.target.classList.contains('btn-delete')) {
            e.preventDefault();
            const deleteUrl = e.target.dataset.url;
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                return;
            }
            try {
                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const result = await response.json();
                if (response.ok) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Gagal menghapus produk.'));
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            }
        }
    });
});
</script>
@endpush
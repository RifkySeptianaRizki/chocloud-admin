<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Semua Produk - Chocloud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1>Daftar Produk Kami</h1>

        <div class="row">
            {{-- Loop melalui setiap produk di koleksi $products --}}
            @forelse ($products as $product)
                <div class="col-md-4 mb-4"> {{-- Menggunakan kolom untuk tata letak grid --}}
                    <div class="card h-100"> {{-- h-100 untuk tinggi kartu yang seragam --}}
                        {{-- SESUAIKAN BARIS INI UNTUK CLOUDINARY --}}
                        @if ($product->image)
                            <img src="{{ $product->image }}" class="card-img-top img-fluid" alt="{{ $product->name }}" style="object-fit: cover; height: 200px;">
                        @else
                            {{-- Placeholder jika tidak ada gambar --}}
                            <img src="https://via.placeholder.com/200x200?text=No+Image" class="card-img-top img-fluid" alt="No Image" style="object-fit: cover; height: 200px;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text lead fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p> {{-- Batasi deskripsi --}}
                            <div class="mt-auto"> {{-- Dorong tombol ke bawah --}}
                                <a href="{{ route('frontend.products.show', $product->id) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                                {{-- Anda bisa juga menambahkan link Shopee/WhatsApp di sini, atau hanya di halaman detail --}}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>Belum ada produk untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Script Bootstrap JS jika diperlukan --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
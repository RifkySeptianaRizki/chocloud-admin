@extends('layouts.admin')
@section('title', 'Manajemen Produk')

{{-- PENTING: Pastikan layout utama Anda punya <meta name="csrf-token" ...> di <head> --}}

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah Produk Baru</a>
    </div>

    {{-- Notifikasi akan ditampilkan oleh JavaScript setelah redirect --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        @if ($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" width="100">
                        @else
                            <img src="https://via.placeholder.com/100?text=No+Image" alt="No Image" width="100">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        
                        {{-- 🔄 PERUBAHAN UTAMA DI SINI: Menggunakan button biasa dengan data-url --}}
                        <button class="btn btn-sm btn-danger btn-delete" 
                                data-url="{{ route('admin.products.destroy', $product->id) }}">
                            Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection


{{-- PENTING: Pastikan layout utama punya @stack('scripts') sebelum </body> --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil CSRF token dari meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Gunakan event delegation untuk semua tombol .btn-delete
    document.body.addEventListener('click', async function(e) {
        if (e.target && e.target.classList.contains('btn-delete')) {
            e.preventDefault();

            const deleteUrl = e.target.dataset.url;

            if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                return;
            }

            try {
                // Kirim request ke controller
                const response = await fetch(deleteUrl, {
                    method: 'POST', // Gunakan POST untuk method spoofing
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE' // Method spoofing untuk Laravel
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    // Jika sukses, tampilkan pesan dan redirect
                    alert(result.message);
                    window.location.href = result.redirect;
                } else {
                    // Jika gagal, tampilkan pesan error
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
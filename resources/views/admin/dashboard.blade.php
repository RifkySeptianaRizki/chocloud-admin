@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="content-card d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 75vh;">
    
    <i class="fas fa-hat-wizard fa-3x mb-4" style="color: #7e57c2;"></i>

    <h1 class="display-5 fw-bold">Selamat Datang, <span class="text-white">{{ Auth::user()->name }}</span>!</h1>
    
    <p class="lead text-white-50 mx-auto" style="max-width: 600px;">
        Ini adalah pusat kendali Chocloud. Kelola produk dan testimoni dengan mudah melalui menu navigasi di atas.
    </p>

    {{-- Ganti class 'btn-primary' menjadi 'btn-glass' di sini --}}
    <a href="{{ route('admin.products.index') }}" class="btn btn-glass btn-lg mt-4">
        <i class="fas fa-box-open me-2"></i>
        Lihat Produk
    </a>

</div>

@endsection
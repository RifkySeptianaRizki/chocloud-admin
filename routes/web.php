<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Penting: Pastikan ini ada
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;

// ----------------------------------------------------------------------
// 1. ROUTE UNTUK HALAMAN AWAL (ROOT URL: /) - PUBLIK & REDIREKSI LOGIN
// Ini akan menjadi route pertama yang dicari.
Route::get('/', function () {
    // Jika pengguna sudah login, arahkan ke dashboard admin
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    // Jika belum login, tampilkan halaman login
    return view('auth.login');
})->name('home'); // Beri nama untuk kemudahan referensi

// ----------------------------------------------------------------------
// 2. ROUTE UNTUK PRODUK FRONTEND (PUBLIK)
// Pindahkan ini keluar dari middleware 'auth' agar bisa diakses publik
Route::get('/products', [FrontendProductController::class, 'index'])->name('frontend.products.index');
Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('frontend.products.show');

// ----------------------------------------------------------------------
// 3. GRUP UNTUK SEMUA ROUTE ADMIN, DILINDUNGI OLEH MIDDLEWARE 'auth' dan 'verified'
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    // Route untuk dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route untuk CRUD produk (admin)
    Route::resource('/products', ProductController::class)->names('admin.products');

    // Route untuk CRUD testimonial (admin)
    // PERHATIAN: Route testimonial ini sebelumnya ada di middleware 'auth' tanpa prefix 'admin'
    // Saya asumsikan ini juga bagian dari admin.
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('admin.testimonials.index');
    Route::patch('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('admin.testimonials.approve');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('admin.testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('admin.testimonials.store');
});

// ----------------------------------------------------------------------
// 4. ROUTE DEFAULT DARI BREEZE UNTUK PROFIL (Hanya perlu 'auth' middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ----------------------------------------------------------------------
// 5. REQUIRE FILE AUTH.PHP DARI BREEZE
require __DIR__.'/auth.php';
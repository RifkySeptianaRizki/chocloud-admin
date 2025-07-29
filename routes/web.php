<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;

// 1. ROUTE UNTUK HALAMAN AWAL (ROOT URL: /)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    return view('auth.login');
})->name('home');

// 2. ROUTE UNTUK PRODUK FRONTEND (PUBLIK)
Route::get('/products', [FrontendProductController::class, 'index'])->name('frontend.products.index');
Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('frontend.products.show');

// 3. GRUP UNTUK SEMUA ROUTE ADMIN
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Route untuk dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Route untuk CRUD produk (admin)
    Route::resource('products', ProductController::class);

    // ⭐ FIX: Rute Testimoni disederhanakan dengan Route::resource ⭐
    // Baris ini secara otomatis membuat route untuk index, create, store, edit, update, destroy
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    
    // Rute custom untuk 'approve' tetap didefinisikan secara terpisah
    Route::patch('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])
         ->name('testimonials.approve');
});

// 4. ROUTE DEFAULT DARI BREEZE UNTUK PROFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. REQUIRE FILE AUTH.PHP DARI BREEZE
require __DIR__.'/auth.php';
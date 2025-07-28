<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;


Route::get('/', function () {
    // Jika sudah login, arahkan ke dashboard admin
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    // Jika belum, tampilkan halaman login
    return view('auth.login');
});

// Grup untuk semua route admin, dilindungi oleh middleware 'auth'
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    // Route untuk dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route untuk CRUD produk
    Route::resource('/products', ProductController::class)->names('admin.products');

});


// Route default dari Breeze untuk profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('admin.testimonials.index');
    Route::patch('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('admin.testimonials.approve');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');
    Route::get('/', [FrontendProductController::class, 'index'])->name('frontend.products.index');
    Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('frontend.products.show');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('admin.testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('admin.testimonials.store');
});

require __DIR__.'/auth.php';
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan halaman daftar semua produk
    public function index()
    {
        $products = Product::latest()->get();
        return view('frontend.products.index', compact('products'));
    }

    // Menampilkan halaman detail satu produk
    public function show(Product $product)
    {
        // Ambil testimoni yang sudah disetujui untuk produk ini
        $approvedTestimonials = $product->testimonials()->where('status', 'approved')->latest()->get();

        return view('frontend.products.show', compact('product', 'approvedTestimonials'));
    }
}
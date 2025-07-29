<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Tambahkan ini jika ingin menampilkan daftar produk di form
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Menampilkan halaman daftar semua testimoni.
     */
    public function index()
    {
        $testimonials = Testimonial::with('product') // Eager load product
                          ->orderBy('status', 'asc')
                          ->latest()
                          ->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Menampilkan form untuk membuat testimoni baru.
     * (Ditambahkan untuk melengkapi fungsionalitas)
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.testimonials.create', compact('products'));
    }

    /**
     * Menyimpan testimoni baru ke database.
     * (Ditambahkan untuk melengkapi fungsionalitas)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'content' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'video_url' => 'nullable|url',
            'status' => 'required|in:pending,approved',
        ]);

        Testimonial::create($request->all());

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimoni baru berhasil ditambahkan.');
    }


    /**
     * Menyetujui sebuah testimoni.
     * (Diubah untuk mengembalikan JSON)
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'approved']);
        
        return response()->json(['message' => 'Testimoni berhasil disetujui!'], 200);
    }

    /**
     * Menghapus sebuah testimoni.
     * (Diubah untuk mengembalikan JSON)
     */
    public function destroy(Testimonial $testimonial)
    {
        // Jika testimoni punya relasi ke file di cloud, logika hapus file bisa ditambahkan di sini
        $testimonial->delete();
        
        return response()->json(['message' => 'Testimoni berhasil dihapus!'], 200);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Menampilkan halaman daftar semua testimoni.
     */
    public function index()
    {
        // Ambil semua testimoni, urutkan berdasarkan status (pending dulu)
        $testimonials = Testimonial::orderBy('status', 'asc')->latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Menyetujui sebuah testimoni.
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'approved']);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil disetujui!');
    }

    /**
     * Menghapus sebuah testimoni.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus!');
    }
}
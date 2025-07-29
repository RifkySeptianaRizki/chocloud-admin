<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman daftar semua produk.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Menyimpan produk baru yang dibuat dari form ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        // Perhatikan: Validasi 'image' sekarang untuk URL, bukan file upload
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|string|url|max:255', // Ganti validasi: required, string, harus URL
            'image_public_id' => 'required|string', // Pastikan public_id juga diterima dan wajib
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        // Karena upload sudah dilakukan di frontend, kita langsung mengambil URL dan public_id
        $imageCloudinaryUrl = $request->input('image');
        $imagePublicId = $request->input('image_public_id');

        // 3. Buat data produk baru di database
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl, // Simpan URL Cloudinary yang diterima dari frontend
            'image_public_id' => $imagePublicId, // Simpan public_id yang diterima dari frontend
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu produk (tidak kita gunakan, jadi kosongkan saja).
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit produk.
     * Laravel otomatis akan mencari produk berdasarkan ID di URL.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Memperbarui data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi
        // Perhatikan: Validasi 'image' sekarang untuk URL, tidak wajib saat update
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|url|max:255', // Ganti validasi: nullable, string, harus URL
            'image_public_id' => 'nullable|string', // Public_id juga tidak wajib jika gambar tidak diganti
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        $imageCloudinaryUrl = $request->input('image'); // Ambil dari input tersembunyi
        $imagePublicId = $request->input('image_public_id'); // Ambil dari input tersembunyi

        // Logika untuk menghapus gambar lama dari Cloudinary jika ada perubahan atau penghapusan gambar
        // Kasus 1: Ada gambar lama, tapi di form tidak ada gambar baru (atau gambar baru null/kosong)
        // Kasus 2: Ada gambar lama, dan ada gambar baru tapi public_id-nya beda (diganti)
        if ($product->image_public_id && // Cek kalau produk punya public_id lama
            ($imagePublicId === null || // Kalau public_id baru kosong (gambar dihapus/tidak diupload)
             $imagePublicId !== $product->image_public_id) // Atau public_id baru beda (gambar diganti)
        ) {
            Cloudinary::destroy($product->image_public_id);
        }


        // 3. Update data produk di database
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl, // Simpan URL Cloudinary yang diterima
            'image_public_id' => $imagePublicId, // Simpan public_id yang diterima
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // 1. Hapus file gambar dari Cloudinary jika ada public_id-nya
        if ($product->image_public_id) {
            Cloudinary::destroy($product->image_public_id);
        }

        // 2. Hapus data produk dari database
        $product->delete();

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
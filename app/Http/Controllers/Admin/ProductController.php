<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
// use Illuminate\Support\Facades\Log; // Opsional: Hapus jika tidak digunakan

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
        // --- PENYESUAIAN: KIRIM CLOUD_NAME KE VIEW ---
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return view('admin.products.create', compact('cloudName'));
        // --- AKHIR PENYESUAIAN ---
    }

    /**
     * Menyimpan produk baru yang dibuat dari form ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|string|url|max:255', // Wajib URL dari Cloudinary
            'image_public_id' => 'required|string',   // Wajib Public ID dari Cloudinary
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        $imageCloudinaryUrl = $request->input('image');
        $imagePublicId = $request->input('image_public_id');

        // 3. Buat data produk baru di database
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl,
            'image_public_id' => $imagePublicId,
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
        // --- PENYESUAIAN: KIRIM CLOUD_NAME KE VIEW ---
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return view('admin.products.edit', compact('product', 'cloudName'));
        // --- AKHIR PENYESUAIAN ---
    }

    /**
     * Memperbarui data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|url|max:255',
            'image_public_id' => 'nullable|string',
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        $imageCloudinaryUrl = $request->input('image');
        $imagePublicId = $request->input('image_public_id');

        if ($product->image_public_id &&
            ($imagePublicId === null ||
             $imagePublicId !== $product->image_public_id)
        ) {
            Cloudinary::destroy($product->image_public_id);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl,
            'image_public_id' => $imagePublicId,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        if ($product->image_public_id) {
            Cloudinary::destroy($product->image_public_id);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
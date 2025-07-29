<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // 👈 Impor model Product
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage; // 👈 TIDAK DIGUNAKAN LAGI UNTUK CLOUDINARY FILE UPLOAD
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // 👈 IMPOR FACADE CLOUDINARY

class ProductController extends Controller
{
    /**
     * Menampilkan halaman daftar semua produk.
     */
    public function index()
    {
        // Ambil semua data produk, urutkan dari yang paling baru
        $products = Product::latest()->get();

        // Tampilkan view dan kirim data products ke dalamnya
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Wajib gambar, maks 2MB
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        // 2. Simpan gambar ke Cloudinary
        $imageCloudinaryUrl = null; // Inisialisasi
        $imagePublicId = null; // Untuk menyimpan public_id jika Anda ingin menghapus nanti

        if ($request->hasFile('image')) {
            // Upload file ke Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'chocloud/products', // Folder di Cloudinary (opsional)
            ]);

            $imageCloudinaryUrl = $uploadedFile->getSecurePath(); // Ambil URL HTTPS gambar
            $imagePublicId = $uploadedFile->getPublicId(); // Ambil Public ID gambar
        }

        // 3. Buat data produk baru di database
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl, // Simpan URL Cloudinary
            'image_public_id' => $imagePublicId, // Simpan public_id (penting untuk hapus/update)
        ]);

        // 4. Redirect kembali ke halaman daftar produk dengan pesan sukses
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
        // 1. Validasi (gambar tidak wajib diisi saat update)
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Tidak wajib
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        $imageCloudinaryUrl = $product->image; // Gunakan URL gambar lama sebagai default
        $imagePublicId = $product->image_public_id; // Gunakan public_id lama sebagai default

        // 2. Cek jika ada gambar baru yang di-upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari Cloudinary jika ada public_id-nya
            if ($product->image_public_id) {
                Cloudinary::destroy($product->image_public_id);
            }
            // Simpan gambar baru ke Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'chocloud/products', // Pastikan folder sama
            ]);
            $imageCloudinaryUrl = $uploadedFile->getSecurePath();
            $imagePublicId = $uploadedFile->getPublicId();
        }

        // 3. Update data produk di database
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'shopee_link' => $request->shopee_link,
            'whatsapp_link' => $request->whatsapp_link,
            'image' => $imageCloudinaryUrl,
            'image_public_id' => $imagePublicId, // Simpan public_id yang diperbarui
        ]);

        // 4. Redirect kembali dengan pesan sukses
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
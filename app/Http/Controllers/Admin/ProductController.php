<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return view('admin.products.create', compact('cloudName'));
    }

    /**
     * Menyimpan produk baru yang dibuat dari form ke database.
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|string|url|max:255',
            'image_public_id' => 'required|string',
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        Product::create($data);

        return response()->json(['message' => 'Produk berhasil ditambahkan!', 'redirect' => route('admin.products.index')], 200);
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        return view('admin.products.edit', compact('product', 'cloudName'));
    }

    /**
     * Memperbarui data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|url|max:255',
            'image_public_id' => 'nullable|string',
            'shopee_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Logika untuk menghapus gambar lama dari Cloudinary jika diganti
        if ($product->image_public_id && (!isset($data['image_public_id']) || $data['image_public_id'] !== $product->image_public_id)) {
            try {
                Cloudinary::destroy($product->image_public_id);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus gambar lama dari Cloudinary: ' . $e->getMessage());
            }
        }

        // Gunakan data baru atau fallback ke data lama jika tidak ada perubahan
        $updateData = [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? $product->description,
            'shopee_link' => $data['shopee_link'] ?? $product->shopee_link,
            'whatsapp_link' => $data['whatsapp_link'] ?? $product->whatsapp_link,
            'image' => $data['image'] ?? $product->image,
            'image_public_id' => $data['image_public_id'] ?? $product->image_public_id,
        ];

        $product->update($updateData);

        return response()->json(['message' => 'Perubahan berhasil disimpan!', 'redirect' => route('admin.products.index')], 200);
    }

    /**
     * Menghapus produk dari database dan gambar dari Cloudinary.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar dari Cloudinary jika ada public_id
        if ($product->image_public_id) {
            try {
                Cloudinary::destroy($product->image_public_id);
            } catch (\Exception $e) {
                Log::error('Gagal menghapus gambar dari Cloudinary: ' . $e->getMessage());
            }
        }

        // Hapus produk dari database
        $product->delete();

        // Kirim respons JSON
        return response()->json([
            'message' => 'Produk berhasil dihapus!',
            'redirect' => route('admin.products.index')
        ], 200);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;      // --- TAMBAHKAN BARIS INI UNTUK LOGGING ---
use Illuminate\Support\Facades\Validator; // --- TAMBAHKAN BARIS INI UNTUK VALIDATOR ---

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
        // --- PERBAIKAN: Gunakan Validator eksplisit untuk JSON request ---
        $data = $request->json()->all(); // Ambil semua data JSON

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
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422); // Status 422 untuk validasi gagal
        }
        // --- AKHIR PERBAIKAN VALIDASI ---

        // Buat data produk baru di database
        Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null, // Tambahkan null coalescing untuk nullable fields
            'shopee_link' => $data['shopee_link'] ?? null,
            'whatsapp_link' => $data['whatsapp_link'] ?? null,
            'image' => $data['image'],
            'image_public_id' => $data['image_public_id'],
        ]);

        // Kirim response JSON untuk AJAX
        return response()->json(['message' => 'Produk berhasil ditambahkan!', 'redirect' => route('admin.products.index')], 200);
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
        // --- DEBUGGING LOGGING START (TETAPKAN UNTUK DIAGNOSIS) ---
        Log::info('--- ProductController@update START ---');
        Log::info('Request Headers (Content-Type): ' . $request->header('Content-Type'));
        Log::info('Request raw input: ' . $request->getContent());
        Log::info('Request all() (traditional): ' . json_encode($request->all()));
        Log::info('Request json()->all() (JSON payload): ' . json_encode($request->json()->all()));
        Log::info('Request input(\'name\') (traditional): ' . $request->input('name'));
        Log::info('Request json(\'name\') (JSON): ' . $request->json('name'));
        Log::info('--- ProductController@update END LOGGING ---');
        // --- DEBUGGING LOGGING END ---

        // --- PERBAIKAN: Gunakan Validator eksplisit untuk JSON request ---
        $data = $request->json()->all(); // Ambil semua data JSON ke dalam array $data

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
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422); // Status 422 untuk validasi gagal
        }
        // --- AKHIR PERBAIKAN VALIDASI ---

        // Logika untuk menghapus gambar lama dari Cloudinary
        if (
            $product->image_public_id && // Cek kalau produk punya public_id lama
            (!isset($data['image_public_id']) || // Kalau public_id baru tidak ada (gambar dihapus)
             $data['image_public_id'] !== $product->image_public_id) // Atau public_id baru beda (gambar diganti)
        ) {
            Cloudinary::destroy($product->image_public_id);
        }

        // Update data produk di database
        $product->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'shopee_link' => $data['shopee_link'] ?? null,
            'whatsapp_link' => $data['whatsapp_link'] ?? null,
            'image' => $data['image'] ?? $product->image, // Gunakan image baru dari data atau image lama dari product
            'image_public_id' => $data['image_public_id'] ?? $product->image_public_id, // Gunakan public_id baru dari data atau public_id lama dari product
        ]);

        // Kirim response JSON untuk AJAX
        return response()->json(['message' => 'Perubahan berhasil disimpan!', 'redirect' => route('admin.products.index')], 200);
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
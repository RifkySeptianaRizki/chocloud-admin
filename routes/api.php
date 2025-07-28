<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// RUTE API UNTUK MENGAMBIL SEMUA PRODUK
Route::get('/products', function () {
    $products = Product::latest()->get();
    return response()->json($products);
});
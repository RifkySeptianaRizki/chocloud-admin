<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Tambahkan relasi ini
public function products()
{
    return $this->hasMany(Product::class);
}
}

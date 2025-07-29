<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'shopee_link',
        'whatsapp_link',
        'image_public_id',
    ];

    public function testimonials()
{
    return $this->hasMany(Testimonial::class);
}
}


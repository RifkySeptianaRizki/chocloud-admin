<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'content',
        'status',
        'product_id',
        'video_url',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
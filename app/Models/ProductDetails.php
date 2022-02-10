<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

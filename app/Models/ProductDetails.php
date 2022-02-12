<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

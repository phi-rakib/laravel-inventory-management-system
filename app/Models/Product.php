<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductDetails;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'summary',
        'brand_id',
        'category_id',
    ];

    public function productDetails()
    {
        return $this->hasOne(ProductDetails::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->created_by = Auth::user()->id;
        });

        static::updating(function ($product) {
            $product->updated_by = Auth::user()->id;
        });
    }
}

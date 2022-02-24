<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductDetails;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function scopeProducts($query)
    {
        return $query->with([
            "brand:id,name",
            "category:id,name",
            "productDetails:id,product_id,description",
        ]);
    }
}

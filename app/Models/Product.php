<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\ProductDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'summary',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function productDetails()
    {
        return $this->hasOne(ProductDetails::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

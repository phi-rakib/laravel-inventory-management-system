<?php

namespace App\Models;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'user_type',
        'status',
        'sub_total',
        'discount',
        'tax',
        'shipping',
        'total',
        'grand_total',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}

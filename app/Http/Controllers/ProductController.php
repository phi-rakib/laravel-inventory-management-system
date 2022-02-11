<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with("productDetails:id,product_id,description")
            ->get();
        return $products;
    }
}

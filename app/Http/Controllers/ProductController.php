<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('q');

        $products = Product::with("productDetails:id,product_id,description")
            ->where('name', 'like', "%{$searchTerm}%")
            ->simplePaginate(10);

        return $products;
    }

    public function show($id)
    {
        $product = Product::with("productDetails:id,product_id,description")
            ->where('id', '=', $id)
            ->get()
            ->first();

        return $product;
    }
}

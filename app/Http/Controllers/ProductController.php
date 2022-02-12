<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        DB::transaction(function () use($request){
            $input = $request->all();

            $product_id = Str::uuid();
            $input['id'] = $product_id;
            
            Product::create($input);

            $input['product_id'] = $input['id'];

            ProductDetails::create($input);
        });
    }
}

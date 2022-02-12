<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('q');

        $products = Product::with("productDetails:id,product_id,description")
            ->where('name', 'like', "%{$searchTerm}%")
            ->orderBy('created_at')
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
        DB::transaction(function () use ($request) {
            $input = $request->all();

            $product_id = Str::uuid();
            $input['id'] = $product_id;

            Product::create($input);

            $input['product_id'] = $input['id'];

            ProductDetails::create($input);
        });
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            ProductDetails::where('product_id', $id)->delete();
            product::destroy($id);
        });

        return response()->json(null, 204);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $product = Product::findOrFail($id);

        DB::transaction(function () use ($input, $product) {
            $product->update($input);
            $product->productDetails->update($input);
        });

        return response()->json($product, 200);
    }
}

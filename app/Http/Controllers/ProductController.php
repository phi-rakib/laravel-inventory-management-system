<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $searchTerm = $request->query('q');

        $products = Product::products();

        if (!empty($searchTerm)) {
            $products = $products->where('name', 'like', "%{$searchTerm}%");
        }

        return $products->orderBy('name')
            ->paginate(Config::get('constants.pagination.max_item'));
    }

    public function show($id)
    {
        return Product::products()->where('id', $id)->get()->first();
    }

    public function store()
    {
        $data = $this->validateRequest();

        DB::transaction(function () use ($data) {

            $product = Product::create($data);

            $data['product_id'] = $product->id;

            ProductDetails::create($data);
        });
    }

    public function update(Product $product)
    {
        $data = $this->validateRequest();

        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $product->productDetails->update($data);
        });

        return response()->json($product, 200);
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product->productDetails->delete();
            $product->delete();
        });

        return response()->json(null, 204);
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'summary' => '',
            'description' => '',
            'brand_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);
    }
}

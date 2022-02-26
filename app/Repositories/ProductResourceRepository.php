<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductDetails;
use App\Repositories\IResourceRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProductResourceRepository implements IResourceRepository
{
    public function getALl()
    {
        $searchTerm = request()->query('q');

        $products = Product::products();

        if (!empty($searchTerm)) {
            $products = $products->where('name', 'like', "%{$searchTerm}%");
        }

        return $products->orderBy('name')
            ->paginate(Config::get('constants.pagination.max_item'));
    }

    public function show($id)
    {
        return Product::products()
            ->where('id', $id)
            ->get()
            ->first();
    }

    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $product = Product::create($data);

            $data['product_id'] = $product->id;

            ProductDetails::create($data);
        });
    }

    public function update($product, $data)
    {
        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $product->productDetails->update($data);
        });

        return $product;
    }

    public function delete($product)
    {
        DB::transaction(function () use ($product) {
            $product->productDetails->delete();
            $product->delete();
        });
    }
}

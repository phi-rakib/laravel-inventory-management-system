<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductDetails;
use App\Repositories\IResourceRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProductResourceRepository implements IResourceRepository
{
    public function getALl($filters = null)
    {
        $searchTerm = $filters['q'] ?? "";

        $products = Product::products();

        if (!empty($searchTerm)) {
            $products = $products->where('name', 'like', "%{$searchTerm}%");
        }

        return $products->orderBy('name')->paginate(
            $filters['perPage'] ?? Config::get('constants.pagination.max_item'),
            ['*'],
            'page',
            $filters['pageNumber'] ?? 1
        );
    }

    public function show($id)
    {
        return Product::products()
            ->where('id', $id)
            ->get()
            ->firstOrFail();
    }

    public function findWhere($column, $value)
    {
        return Product::where($column, $value)->firstOrFail();
    }

    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $product = Product::create($data);

            $data['product_id'] = $product->id;

            ProductDetails::create($data);
        });
    }

    public function update($id, $data)
    {
        $product = $this->getProductById($id);

        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $product->productDetails->update($data);
        });

        return $product;
    }

    public function delete($id)
    {
        $product = $this->getProductById($id);

        DB::transaction(function () use ($product) {
            $product->productDetails->delete();
            $product->delete();
        });
    }

    private function getProductById($id)
    {
        return Product::findOrFail($id);
    }
}

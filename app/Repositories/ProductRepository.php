<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getALl($filters = null)
    {
        $searchTerm = $filters['q'] ?? "";

        $products = $this->model::products();

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
        return $this->model::products()->findOrFail($id);
    }

    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $product = $this->model::create($data);

            $data['product_id'] = $product->id;

            ProductDetails::create($data);
        });
    }

    public function update($id, $data)
    {
        $product = parent::show($id);

        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $product->productDetails->update($data);
        });

        return $product;
    }

    public function delete($id)
    {
        $product = parent::show($id);

        DB::transaction(function () use ($product) {
            $product->productDetails->delete();
            $product->delete();
        });
    }
}

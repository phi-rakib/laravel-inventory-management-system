<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Repositories\IResourceRepository;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(IResourceRepository $productRepository)
    {
        $this->middleware('auth:sanctum');
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return $this->productRepository->getAll();
    }

    public function show($id)
    {
        return $this->productRepository->show($id);
    }

    public function store(ProductRequest $request)
    {
        $this->productRepository->create($request->validated());
    }

    public function update(Product $product, ProductRequest $request)
    {
        return $this->productRepository->update($product, $request->validated());
    }

    public function destroy(Product $product)
    {
        $this->productRepository->delete($product);

        return response()->json(null, 204);
    }
}

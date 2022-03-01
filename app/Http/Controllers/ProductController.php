<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->middleware('auth:sanctum');
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $filters = [
            'perPage' => Config::get('constants.pagination.max_item'),
            'pageNumber' => request()->query('page'),
            'q' => request()->query('q'),
        ];

        return $this->productRepository->getAll($filters);
    }

    public function show($id)
    {
        return $this->productRepository->show($id);
    }

    public function store(ProductRequest $request)
    {
        $this->productRepository->create($request->validated());
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->productRepository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        $this->productRepository->delete($id);

        return response()->json(null, 204);
    }
}

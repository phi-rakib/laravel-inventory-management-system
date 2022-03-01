<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Repositories\BrandRepositoryInterface;
use Illuminate\Support\Facades\Config;

class BrandController extends Controller
{
    private $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->middleware('auth:sanctum');
        $this->brandRepository = $brandRepository;
    }

    public function index()
    {
        $filters = [
            'perPage' => Config::get('constants.pagination.max_item'),
            'pageNumber' => request()->query('page'),
            'q' => request()->query('q'),
        ];

        return $this->brandRepository->getAll($filters);
    }

    public function show($id)
    {
        return $this->brandRepository->show($id);
    }

    public function store(BrandRequest $request)
    {
        $this->brandRepository->create($request->validated());
    }

    public function update($id, BrandRequest $request)
    {
        $this->brandRepository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        $this->brandRepository->delete($id);

        return response()->json(null, 204);
    }
}

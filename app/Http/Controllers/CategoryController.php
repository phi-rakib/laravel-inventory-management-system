<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->middleware('auth:sanctum');
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->categoryRepository->getAll();
    }

    public function show($id)
    {
        return $this->categoryRepository->show($id);
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryRepository->create($request->validated());
    }

    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryRepository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        $this->categoryRepository->delete($id);
    }
}

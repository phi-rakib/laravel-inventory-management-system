<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\IResourceRepository;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(IResourceRepository $categoryRepository)
    {
        $this->middleware('auth:sanctum');
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->categoryRepository->getAll();
    }

    public function show(Category $category)
    {
        return $this->categoryRepository->show($category);
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryRepository->create($request->validated());
    }

    public function update(Category $category, CategoryRequest $request)
    {
        return $this->categoryRepository->update($category, $request->validated());
    }

    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\IResourceRepository;

class CategoryController extends Controller
{
    private $category_repository;

    public function __construct(IResourceRepository $category_repository)
    {
        $this->middleware('auth:sanctum');
        $this->category_repository = $category_repository;
    }

    public function index()
    {
        return $this->category_repository->getAll();
    }

    public function show(Category $category)
    {
        return $this->category_repository->show($category);
    }

    public function store(CategoryRequest $request)
    {
        return $this->category_repository->create($request->validated());
    }

    public function update(Category $category, CategoryRequest $request)
    {
        return $this->category_repository->update($category, $request->validated());
    }

    public function destroy(Category $category)
    {
        $this->category_repository->delete($category);
    }
}

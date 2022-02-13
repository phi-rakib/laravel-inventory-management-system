<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with("children")
            ->whereNull('parent_id')
            ->get();
        return $categories;
    }
    
    public function store(Request $request)
    {
        $input = $request->all();

        $category = Category::create($input);

        return $category;
    }
}

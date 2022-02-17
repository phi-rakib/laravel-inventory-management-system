<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $parents = Category::whereNull('parent_id')->get();

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('parent_id')->get();

        return $this->categoryTree($parents, $categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        $sub_categories = $category->children;

        return $sub_categories;
    }

    public function store()
    {
        return Category::create($this->validateRequest());
    }

    public function update(Category $category)
    {
        $category->update($this->validateRequest());
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'parent_id' => 'nullable|numeric',
        ]);
    }

    private function categoryTree($parents, $categories)
    {
        $tree = [];
        foreach ($parents as $parent) {
            $node = new Node($parent->name);
            $tree[] = $node;
            $this->dfs($categories, $node, $parent->id);
        }

        return $tree;
    }

    private $explore = [];

    private function dfs($graph, $node, $key)
    {
        $this->explore[$key] = 1;
        foreach ($graph as $g) {
            if ($g->parent_id == $key && !isset($explore[$g->id])) {
                $tmp_node = new Node($g->name);
                $node->children[] = $tmp_node;
                $this->dfs($graph, $tmp_node, $g->id);
            }
        }
    }
}

class Node
{
    public $name;
    public $children;

    public function __construct($name)
    {
        $this->name = $name;
        $this->children = [];
    }
}

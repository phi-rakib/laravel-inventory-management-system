<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryResourceRepository implements IResourceRepository
{
    public function getAll()
    {
        $parents = Category::whereNull('parent_id')->get();

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('parent_id')->get();

        return $this->categoryTree($parents, $categories);
    }

    public function show($category)
    {
        return $category->children;
    }

    public function create($data)
    {
        return Category::create($data);
    }

    public function update($category, $data)
    {
        return $category->update($data);
    }

    public function delete($category)
    {
        $categories = Category::all(['id', 'parent_id']);

        $result = [];
        $color = [];
        foreach ($categories as $parent) {
            if ($parent->id == $category->id) {
                $result[] = $category->id;
                $this->collectIds($parent->id, $result, $categories, $color);
                break;
            }
        }
        Category::destroy($result);
    }

    private function collectIds($parentId, &$result, $categories, &$color)
    {
        $color[$parentId] = 1;
        foreach ($categories as $category) {
            if (!isset($color[$category->id]) && $parentId == $category->parent_id) {
                $result[] = $category->id;
                $this->collectIds($category->id, $result, $categories, $color);
            }
        }
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

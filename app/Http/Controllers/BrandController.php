<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Config;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return Brand::paginate(Config::get('constants.pagination.max_item'));
    }

    public function show(Brand $brand)
    {
        return $brand;
    }

    public function store()
    {
        Brand::create($this->validateRequest());
    }

    public function update(Brand $brand)
    {
        $brand->update($this->validateRequest());
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
    }

    protected function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'description' => '',
        ]);
    }
}

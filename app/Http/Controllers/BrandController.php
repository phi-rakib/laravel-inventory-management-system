<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
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

    public function store(BrandRequest $request)
    {
        Brand::create($request->validated());
    }

    public function update(Brand $brand, BrandRequest $request)
    {
        $brand->update($request->validated());
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
    }
}

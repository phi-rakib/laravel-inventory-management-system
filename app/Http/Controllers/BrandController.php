<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function store()
    {
        Brand::create($this->validateRequest());
    }

    public function update(Brand $brand)
    {
        $brand->update($this->validateRequest());
    }

    protected function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'description' => '',
        ]);
    }
}

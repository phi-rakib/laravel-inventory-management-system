<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

    /** @test */
    public function a_product_can_be_added()
    {
        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response->assertOk();

        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => '',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_id_is_required()
    {
        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_brand_id_should_be_numeric()
    {
        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'category_id' => $category->id,
            'brand_id' => 'abc',
        ]);

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_category_id_required()
    {
        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_category_id_should_be_numeric()
    {
        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
            'category_id' => 'abc',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_product_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response->assertOk();

        $product = Product::first();

        $this->put('/api/product/' . $product->id, [
            'name' => 'cocola cook noodles',
            'summary' => 'cocola cook noodles summary',
            'description' => 'cocola cook noodles description',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $updated_product = Product::with('productDetails')->first();

        $this->assertEquals('cocola cook noodles', $updated_product->name);
        $this->assertEquals('cocola cook noodles summary', $updated_product->summary);
        $this->assertEquals($brand->id, $updated_product->brand_id);
        $this->assertEquals($category->id, $updated_product->category_id);
        $this->assertEquals('cocola cook noodles description', $updated_product->productDetails->description);
    }


    /** @test */
    public function a_product_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/brand', [
            'name' => 'ABC',
            'description' => 'ABC Group',
        ]);

        $brand = Brand::first();

        $this->post('/api/category', [
            'name' => 'XYZ',
            'parent_id' => null,
        ]);

        $category = Category::first();

        $response = $this->post('/api/product', [
            'name' => 'coca cola',
            'summary' => 'coca cola summary',
            'description' => 'coca cola description',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response->assertOk();
        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());

        $product = Product::first();

        $this->delete('/api/product/' . $product->id);

        $this->assertCount(0, Product::all());
        $this->assertCount(0, ProductDetails::all());
    }

}

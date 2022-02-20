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
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertOk();

        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'name' => '',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_id_is_required()
    {
        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_brand_id_should_be_numeric()
    {
        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => 'abc',
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_category_id_required()
    {
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_category_id_should_be_numeric()
    {
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => 'abc',
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_product_can_be_updated()
    {
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertOk();

        $product = Product::first();

        $tmp_product = Product::factory()->state([
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ])->make();

        $tmp_product_details = ProductDetails::factory()->make();

        $response = $this->put('/api/product/' . $product->id, array_merge($tmp_product->toArray(), $tmp_product_details->toArray()));

        $updated_product = Product::with('productDetails')->first();

        $this->assertEquals($tmp_product->name, $updated_product->name);
        $this->assertEquals($tmp_product->summary, $updated_product->summary);
        $this->assertEquals($tmp_product->brand_id, $updated_product->brand_id);
        $this->assertEquals($tmp_product->category_id, $updated_product->category_id);
        $this->assertEquals($tmp_product_details->description, $updated_product->productDetails->description);
    }

    /** @test */
    public function a_product_can_be_deleted()
    {
        Brand::factory()->count(5)->create();
        $brand = Brand::inRandomOrder()->first();

        Category::factory()->count(5)->child()->create();
        $category = Category::inRandomOrder()->first();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post('/api/product', array_merge($product, $product_details));

        $response->assertOk();
        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());

        $product = Product::first();

        $this->delete('/api/product/' . $product->id);

        $this->assertCount(0, Product::all());
        $this->assertCount(0, ProductDetails::all());
    }

}

<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
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
    public function should_fetch_all_the_products()
    {
        $this->seed([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $response = $this->get(route('product.index'));

        $response->assertOk();

        $this->assertEquals(Config::get('constants.test.product.max_item'), $response['total']);
    }

    /** @test */
    public function paginate_should_return_n_products_per_page()
    {
        $this->seed([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $response = $this->get(route('product.index'));

        $response->assertOk();

        $this->assertLessThanOrEqual(Config::get('constants.pagination.max_item'), count($response['data']));
    }

    /** @test */
    public function should_fetch_product_by_id()
    {
        $this->seed([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $product = Product::inRandomOrder()->first();

        $response = $this->get(route('product.show', ['product' => $product->id]));

        $response->assertOk();

        $response->assertJson([
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }

    /** @test */
    public function a_product_can_be_added()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertOk();

        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();

        $product = Product::factory()->state([
            'name' => '',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_id_is_required()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->state([
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_brand_id_should_be_numeric()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => 'abc',
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertSessionHasErrors('brand_id');
    }

    /** @test */
    public function a_category_id_required()
    {
        $brand = Brand::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_category_id_should_be_numeric()
    {
        $brand = Brand::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => 'abc',
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function a_product_can_be_updated()
    {
        Brand::factory()->count(5)->create();

        Category::factory()->count(5)->create();

        $product = Product::factory()->state([
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertOk();

        $product = Product::first();

        $tmp_product = Product::factory()->state([
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ])->make();

        $tmp_product_details = ProductDetails::factory()->make();

        $response = $this->put(
            route('product.update', ['product' => $product->id]),
            array_merge($tmp_product->toArray(), $tmp_product_details->toArray())
        );

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
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])->make()->toArray();

        $product_details = ProductDetails::factory()->make()->toArray();

        $response = $this->post(route('product.store'), array_merge($product, $product_details));

        $response->assertOk();
        $this->assertCount(1, Product::all());
        $this->assertCount(1, ProductDetails::all());

        $product = Product::first();

        $this->delete(route('product.destroy', ['product' => $product->id]));

        $this->assertCount(0, Product::all());
        $this->assertCount(0, ProductDetails::all());
    }

}

<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\User;
use App\Repositories\ProductRepository;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProductRepositoryUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_can_be_created()
    {
        Event::fake();

        $user = User::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => Brand::factory()->state(['created_by' => $user->id])->create(),
            'category_id' => Category::factory()->create(),
            'created_by' => $user->id,
        ])->make();

        $productDetails = ProductDetails::factory()->make();

        $data = array_merge($product->toArray(), $productDetails->toArray());

        $respository = new ProductRepository();
        $respository->create($data);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_details', 1);

        $this->assertDatabaseHas('products', ['name' => $product->name]);
        $this->assertDatabaseHas('product_details', ['description' => $productDetails->description]);
    }

    /** @test */
    public function a_product_can_be_updated()
    {
        Event::fake();

        $user = User::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => Brand::factory()->state(['created_by' => $user->id])->create(),
            'category_id' => Category::factory()->create(),
            'created_by' => $user->id,
        ])->create();

        $productDetails = ProductDetails::factory()->state(['product_id' => $product->id])->create();

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_details', 1);

        $tmpProduct = Product::factory()->state([
            'brand_id' => Brand::factory()->state(['created_by' => $user->id])->create(),
            'category_id' => Category::factory()->create(),
            'created_by' => $user->id,
        ])->make()->toArray();

        $tmpProductDetails = ProductDetails::factory()->state(['product_id' => $productDetails->product_id])->make()->toArray();

        $respository = new ProductRepository();
        $respository->update($product->id, array_merge($tmpProduct, $tmpProductDetails));

        $this->assertDatabaseHas('products', $tmpProduct);
        $this->assertDatabaseHas('product_details', $tmpProductDetails);
    }

    /** @test */
    public function a_product_can_be_deleted()
    {
        Event::fake();

        $user = User::factory()->create();

        $product = Product::factory()->state([
            'brand_id' => Brand::factory()->state(['created_by' => $user->id])->create(),
            'category_id' => Category::factory()->create(),
            'created_by' => $user->id,
        ])->create();

        ProductDetails::factory()->state(['product_id' => $product->id])->create();

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_details', 1);

        $respository = new ProductRepository();
        $respository->delete($product->id);

        $this->assertCount(0, Product::all());
        $this->assertCount(0, ProductDetails::all());

        $this->assertCount(1, Product::withTrashed()->get());
        $this->assertCount(1, ProductDetails::withTrashed()->get());
    }

    /** @test */
    public function should_get_all_the_products()
    {
        $this->initSeeder();

        $respository = new ProductRepository();
        $products = $respository->getALl()->toArray();

        $this->assertEquals(Config::get('constants.test.product.max_item'), $products['total']);
    }

    /** @test */
    public function pagination_should_return_less_or_equal_n_products_per_page()
    {
        $this->initSeeder();

        $respository = new ProductRepository();
        $products = $respository->getALl()->toArray();

        $this->assertLessThanOrEqual(Config::get('constants.pagination.max_item'), count($products['data']));
    }

    private function initSeeder()
    {
        $this->seed([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }

}

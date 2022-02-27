<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\User;
use App\Repositories\ProductResourceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProductResourceRepositoryTest extends TestCase
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

        $respository = new ProductResourceRepository();
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

        $respository = new ProductResourceRepository();
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

        $respository = new ProductResourceRepository();
        $respository->delete($product->id);

        $this->assertCount(0, Product::all());
        $this->assertCount(0, ProductDetails::all());

        $this->assertCount(1, Product::withTrashed()->get());
        $this->assertCount(1, ProductDetails::withTrashed()->get());
    }

}

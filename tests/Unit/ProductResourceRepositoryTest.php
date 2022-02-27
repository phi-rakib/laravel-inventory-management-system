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
}

<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductManagementUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_soft_delete_should_work_properly()
    {
        $user = User::factory()
            ->state(['role_id' => Config::get('constants.roles.admin')])
            ->create();
        Sanctum::actingAs($user, ['*']);

        $this->seed([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->assertCount(Config::get('constants.test.product.max_item'), Product::all());

        $product = Product::inRandomOrder()->first();

        $product->productDetails->delete();
        $product->delete();

        $this->assertCount(Config::get('constants.test.product.max_item') - 1, Product::all());
        $this->assertCount(Config::get('constants.test.product.max_item') - 1, ProductDetails::all());

        $this->assertCount(Config::get('constants.test.product.max_item'), Product::withTrashed()->get());
        $this->assertCount(Config::get('constants.test.product.max_item'), ProductDetails::withTrashed()->get());
    }

    /** @test */
    public function a_product_has_one_brand()
    {
        $product = new Product();
        $foreign_key = 'brand_id';

        $relationship = $product->brand();
        $related_model = $relationship->getRelated();

        $this->assertInstanceOf(BelongsTo::class, $relationship);

        $this->assertInstanceOf(Brand::class, $related_model);

        $this->assertEquals($foreign_key, $relationship->getForeignKeyName());

        $this->assertTrue(Schema::hasColumns($relationship->getParent()->getTable(), [$foreign_key]));
    }

    /** @test */
    public function product_should_have_a_scope_method_named_products()
    {
        $this->assertInstanceOf(Builder::class, Product::products());
    }
    
}

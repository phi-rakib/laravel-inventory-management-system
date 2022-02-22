<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
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

        $product->delete();

        $this->assertCount(Config::get('constants.test.product.max_item') - 1, Product::all());

        $this->assertCount(Config::get('constants.test.product.max_item'), Product::withTrashed()->get());
    }

}

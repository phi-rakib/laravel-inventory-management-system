<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class BrandManagementUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_identify_creator_of_a_brand_by_user_id()
    {   
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $brand = Brand::factory()->create();
        $this->assertEquals($user->id, $brand->created_by);
    }

    /** @test */
    public function can_identify_updater_of_a_brand_by_user_id()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $brand = Brand::factory()->create();
        $tmp_brand = Brand::factory()->make();

        $brand->update($tmp_brand->toArray());

        $updated_brand = Brand::first();

        $this->assertEquals($user->id, $updated_brand->updated_by);
    }

    /** @test */
    public function soft_delete_is_working_properly()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $brand = Brand::factory()->create();
        $this->assertCount(1, Brand::all());

        $brand->delete();
        $this->assertCount(0, Brand::all());
        
        $this->assertCount(1, Brand::withTrashed()->get());
    }

    /** @test */
    public function a_brand_has_many_products()
    {
        $brand = new Brand();
        $foreign_key = 'brand_id';

        $relationship = $brand->products();
        $related_model = $relationship->getRelated();
        
        $this->assertInstanceOf(HasMany::class, $relationship);
        $this->assertInstanceOf(Product::class, $related_model);

        $this->assertEquals($foreign_key, $relationship->getForeignKeyName());

        $this->assertTrue(Schema::hasColumns($related_model->getTable(), [$foreign_key]));
    }
}

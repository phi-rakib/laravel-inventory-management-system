<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

    /** @test */
    public function should_fetch_all_the_brands()
    {
        $this->seed(BrandSeeder::class);

        $response = $this->get(route('brand.index'));

        $response->assertOk();

        $this->assertEquals(Config::get('constants.test.brand.max_item'), $response['total']);
    }

    /** @test */
    public function pagination_should_show_n_brands_per_page()
    {
        $this->seed(BrandSeeder::class);

        $response = $this->get(route('brand.index'));

        $response->assertOk();

        $this->assertLessThanOrEqual(Config::get('constants.pagination.max_item'), count($response['data']));
    }

    /** @test */
    public function should_fetch_brand_by_id()
    {
        $this->withoutExceptionHandling();

        Brand::factory()->count(10)->create();

        $brand = Brand::inRandomOrder()->first();

        $response = $this->get(route('brand.show', ['brand' => $brand->id]));

        $response->assertOk();

        $response->assertJson(['id' => $brand->id]);
    }

    /** @test */
    public function a_brand_can_be_added()
    {
        $brand = Brand::factory()->make()->toArray();

        $response = $this->post(route('brand.store'), $brand);

        $response->assertOk();

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $brand = Brand::factory()->state(['name' => ''])->make()->toArray();

        $response = $this->post(route('brand.store'), $brand);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_can_be_updated()
    {
        $this->post(route('brand.store'), Brand::factory()->make()->toArray());

        $brand = Brand::first();

        $tmpBrand = Brand::factory()->make();

        $this->put(route('brand.update', ['brand' => $brand->id]), $tmpBrand->toArray());

        $updated_brand = Brand::first();

        $this->assertEquals($tmpBrand->name, $updated_brand->name);
        $this->assertEquals($tmpBrand->description, $updated_brand->description);
    }

    /** @test */
    public function a_brand_can_be_deleted()
    {
        $this->post(route('brand.store'), Brand::factory()->make()->toArray());

        $this->assertCount(1, Brand::all());

        $brand = Brand::first();

        $this->delete(route('brand.destroy', ['brand' => $brand->id]));

        $this->assertCount(0, Brand::all());
    }
}

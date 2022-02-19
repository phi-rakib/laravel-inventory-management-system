<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
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
        Brand::factory()->count(3)->create();

        $response = $this->get('/api/brand');

        $response->assertOk();

        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function pagination_should_show_n_items_per_page()
    {
        Brand::factory()->count(25)->create();

        $response = $this->get('/api/brand');

        $response->assertOk();

        $response->assertJsonCount(Config::get('constants.pagination.max_item'), 'data');
    }

    /** @test */
    public function should_fetch_brand_by_id()
    {
        $this->withoutExceptionHandling();

        Brand::factory()->count(10)->create();

        $brand = Brand::inRandomOrder()->first();

        $response = $this->get('/api/brand/' . $brand->id);

        $response->assertOk();

        $response->assertJson(['id' => $brand->id]);
    }

    /** @test */
    public function a_brand_can_be_added()
    {
        $brand = Brand::factory()->make()->toArray();

        $response = $this->post('/api/brand', $brand);

        $response->assertOk();

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $brand = Brand::factory()->state(['name' => ''])->make()->toArray();

        $response = $this->post('/api/brand', $brand);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_can_be_updated()
    {
        $this->post('/api/brand', Brand::factory()->make()->toArray());

        $brand = Brand::first();

        $tmpBrand = Brand::factory()->make();

        $this->put($brand->path(), $tmpBrand->toArray());

        $updated_brand = Brand::first();

        $this->assertEquals($tmpBrand->name, $updated_brand->name);
        $this->assertEquals($tmpBrand->description, $updated_brand->description);
    }

    /** @test */
    public function a_brand_can_be_deleted()
    {
        $this->post('/api/brand', Brand::factory()->make()->toArray());

        $this->assertCount(1, Brand::all());

        $brand = Brand::first();

        $this->delete($brand->path());

        $this->assertCount(0, Brand::all());
    }
}

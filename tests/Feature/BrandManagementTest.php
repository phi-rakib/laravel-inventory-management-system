<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::create([
            'role_id' => 1,
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]), ['*']);
    }

    /** @test */
    public function a_brand_can_be_added()
    {
        $response = $this->post('/api/brand', [
            'name' => 'Pran',
            'description' => 'Pran Group',
        ]);

        $response->assertOk();

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $response = $this->post('/api/brand', [
            'name' => '',
            'description' => 'Pran Group',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_can_be_updated()
    {
        $this->post('/api/brand', [
            'name' => 'Pran',
            'description' => 'Pran Group',
        ]);

        $brand = Brand::first();

        $this->put($brand->path(), [
            'name' => 'Pran RFL',
            'description' => 'Pran RFL Group',
        ]);

        $brand = Brand::first();

        $this->assertEquals('Pran RFL', $brand->name);
        $this->assertEquals('Pran RFL Group', $brand->description);
    }

    /** @test */
    public function a_brand_can_be_deleted()
    {
        $this->post('/api/brand', [
            'name' => 'Pran',
            'description' => 'Pran Group',
        ]);

        $brand = Brand::first();

        $this->assertCount(1, Brand::all());

        $this->delete($brand->path());

        $this->assertCount(0, Brand::all());
    }
}

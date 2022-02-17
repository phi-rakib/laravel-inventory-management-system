<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_brand_can_be_added()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/brand', [
            'name' => 'Pran',
            'description' => 'Pran Group'
        ]);

        $response->assertOk();

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $response = $this->post('/api/brand', [
            'name' => '',
            'description' => 'Pran Group'
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_brand_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/brand', [
            'name' => 'Pran',
            'description' => 'Pran Group'
        ]);

        $brand = Brand::first();

        $this->put('/api/brand/'.$brand->id, [
            'name' => 'Pran RFL',
            'description' => 'Pran RFL Group'
        ]);

        $brand = Brand::first();
        

        $this->assertEquals('Pran RFL', $brand->name);
        $this->assertEquals('Pran RFL Group', $brand->description);
    }
    
    
}

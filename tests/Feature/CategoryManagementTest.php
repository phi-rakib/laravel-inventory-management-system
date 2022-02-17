<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_can_be_added()
    {
        $response = $this->post('/api/category', [
            'name' => 'noodles',
            'parent_id' => null,
        ]);

        $response->assertCreated();

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $response = $this->post('/api/category', [
            'name' => '',
            'parent_id' => null,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function parent_id_can_be_nullable_or_numeric()
    {
        $response = $this->post('/api/category', [
            'name' => '',
            'parent_id' => 'abc',
        ]);

        $response->assertSessionHasErrors('parent_id');
    }

}

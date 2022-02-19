<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

    /** @test */
    public function should_fetch_all_categories()
    {
        Category::factory()->count(5)->child()->create();

        $response = $this->get('/api/category');

        $response->assertOk();

        $response->assertJsonCount(5);
    }

    /** @test */
    public function a_category_can_be_added()
    {
        $category = Category::factory()->make()->toArray();

        $response = $this->post('/api/category', $category);

        $response->assertCreated();

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $category = Category::factory()->state(['name' => ''])->make()->toArray();

        $response = $this->post('/api/category', $category);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function parent_id_can_be_nullable_or_numeric()
    {
        $category = Category::factory()->state(['parent_id' => 'abc'])->make()->toArray();

        $response = $this->post('/api/category', $category);

        $response->assertSessionHasErrors('parent_id');
    }

    /** @test */
    public function a_category_can_be_updated()
    {
        $response = $this->post('/api/category', Category::factory()->make()->toArray());

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $tmpCategory = Category::factory()->state(['parent_id' => 1])->make();

        $this->put('/api/category/' . $category->id, $tmpCategory->toArray());

        $updated_category = Category::first();

        $this->assertEquals($tmpCategory->name, $updated_category->name);
        $this->assertEquals($tmpCategory->parent_id, $updated_category->parent_id);
    }

    /** @test */
    public function a_category_can_be_deleted()
    {
        $response = $this->post('/api/category', Category::factory()->make()->toArray());

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $this->delete('/api/category/' . $category->id);

        $this->assertCount(0, Category::all());
    }

}

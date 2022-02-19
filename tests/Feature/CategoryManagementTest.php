<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryManagementTest extends TestCase
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
    public function should_fetch_all_categories()
    {
        $this->withoutExceptionHandling();

        Category::factory()->count(5)->child()->create();
        
        $response = $this->get('/api/category');

        $response->assertOk();

        $response->assertJsonCount(5);
    }
    

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

    /** @test */
    public function a_category_can_be_updated()
    {
        $response = $this->post('/api/category', [
            'name' => 'noodles',
            'parent_id' => null,
        ]);

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $this->put('/api/category/' . $category->id, [
            'name' => 'cook noodles',
            'parent_id' => 1,
        ]);

        $updated_category = Category::first();
        $this->assertEquals('cook noodles', $updated_category->name);
        $this->assertEquals(1, $updated_category->parent_id);
    }

    /** @test */
    public function a_category_can_be_deleted()
    {
        $response = $this->post('/api/category', [
            'name' => 'noodles',
            'parent_id' => null,
        ]);

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $this->delete('/api/category/' . $category->id);

        $this->assertCount(0, Category::all());
    }

}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
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
        $this->seed(CategorySeeder::class);

        $response = $this->get(route('category.index'));

        $response->assertOk();

        $response->assertJsonCount(Config::get('constants.test.category.total_parent_item'));
    }

    /** @test */
    public function a_category_can_be_added()
    {
        $category = Category::factory()->make()->toArray();

        $response = $this->post(route('category.store'), $category);

        $response->assertCreated();

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function a_name_is_required()
    {
        $category = Category::factory()->state(['name' => ''])->make()->toArray();

        $response = $this->post(route('category.store'), $category);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function parent_id_can_be_nullable_or_numeric()
    {
        $category = Category::factory()->state(['parent_id' => 'abc'])->make()->toArray();

        $response = $this->post(route('category.store'), $category);

        $response->assertSessionHasErrors('parent_id');
    }

    /** @test */
    public function a_category_can_be_updated()
    {
        $response = $this->post(route('category.store'), Category::factory()->make()->toArray());

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $tmpCategory = Category::factory()->state(['parent_id' => 1])->make();

        $this->put(route('category.update', ['category' => $category->id]), $tmpCategory->toArray());

        $updated_category = Category::first();

        $this->assertEquals($tmpCategory->name, $updated_category->name);
        $this->assertEquals($tmpCategory->parent_id, $updated_category->parent_id);
    }

    /** @test */
    public function a_category_can_be_deleted()
    {
        $response = $this->post(route('category.store'), Category::factory()->make()->toArray());

        $response->assertCreated();

        $this->assertCount(1, Category::all());

        $category = Category::first();

        $this->delete(route('category.destroy', ['category' => $category->id]));

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function deleting_parent_should_delete_all_the_children()
    {
        $manually_generated = 12;
        $seed_generated = Config::get('constants.test.category.total_parent_item') + 25;
        $total_category = $seed_generated + $manually_generated;

        $this->seed(CategorySeeder::class);

        $category_1 = Category::factory()->create();

        $category_2 = Category::factory()->state(['parent_id' => $category_1->id])->create();
        $category_3 = Category::factory()->state(['parent_id' => $category_1->id])->create();

        Category::factory()->state(['parent_id' => $category_2->id])->create();
        Category::factory()->state(['parent_id' => $category_2->id])->count(5)->create();

        Category::factory()->state(['parent_id' => $category_3->id])->count(3)->create();

        $this->assertCount($total_category, Category::all());

        $this->delete(route('category.destroy', ['category' => $category_1->id]));

        $this->assertCount($total_category - $manually_generated, Category::all());
    }

}

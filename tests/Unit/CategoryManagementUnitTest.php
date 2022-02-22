<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class CategoryManagementUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_has_many_products()
    {
        $category = new Category();
        $foreign_key = 'category_id';

        $relationship = $category->products();
        $related_model = $relationship->getRelated();

        $this->assertInstanceOf(HasMany::class, $relationship);

        $this->assertInstanceOf(Product::class, $related_model);

        $this->assertEquals($foreign_key, $relationship->getForeignKeyName());

        $this->assertTrue(Schema::hasColumns($related_model->getTable(), [$foreign_key]));
    }
    
}

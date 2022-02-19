<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'parent_id' => null,
        ];
    }

    public function child()
    {
        return $this->state(function(array $attributes){
            return [
                'parent_id' => Category::factory()->create()
            ];
        });
    }
}

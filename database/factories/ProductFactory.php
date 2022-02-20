<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->domainWord(),
            'summary' => $this->faker->sentence(),
            'brand_id' => null,
            'category_id' => null,
            'created_by' => null,
        ];
    }
}

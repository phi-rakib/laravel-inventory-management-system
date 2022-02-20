<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::withoutEvents(function () {
            Product::factory()
                ->hasProductDetails(1, function (array $attributes, Product $product) {
                    return [
                        'product_id' => $product->id,
                    ];
                })
                ->count(Config::get('constants.test.product.max_item'))
                ->state(new Sequence(
                    fn($sequence) => [
                        'brand_id' => Brand::inRandomOrder()->first()->id,
                        'category_id' => Category::inRandomOrder()->first()->id,
                        'created_by' => User::inRandomOrder()->first()->id,
                    ]
                ))->create();
        });

    }
}

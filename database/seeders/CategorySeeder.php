<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(Config::get('constants.test.category.total_parent_item'))->create();

        foreach(range(1, 5) as $count) {
            Category::factory()->count(5)->state(new Sequence(
                fn($sequence) => ['parent_id' => Category::inRandomOrder()->first()->id]
            ))->create();
        }
    }
}

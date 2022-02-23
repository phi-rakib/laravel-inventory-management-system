<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::withoutEvents(function () {
            Brand::factory()
                ->count(Config::get('constants.test.brand.max_item'))
                ->state(new Sequence(
                    fn($sequence) => [
                        'created_by' => User::inRandomOrder()->first()->id,
                    ]
                ))->create();
        });
    }
}

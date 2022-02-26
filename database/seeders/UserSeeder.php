<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->state([
            'email' => 'admin@test.com',
            'password' => '123456',
        ])->create();

        User::factory()
            ->count(Config::get('constants.test.user.max_item') - 1)
            ->state(new Sequence(
                fn($sequence) => ['role_id' => rand(1, 4)]
            ))->create();
    }
}

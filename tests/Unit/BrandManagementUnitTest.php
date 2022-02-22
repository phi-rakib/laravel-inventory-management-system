<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Brand;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandManagementUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_identify_creator_of_a_brand_by_user_id()
    {   
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $brand = Brand::factory()->create();
        $this->assertEquals($user->id, $brand->created_by);
    }
}

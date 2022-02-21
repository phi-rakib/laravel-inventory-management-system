<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

    /** @test */
    public function should_fetch_all_users()
    {
        $this->seed(UserSeeder::class);

        $response = $this->get(route('user.index'));

        $response->assertOk();

        $this->assertEquals(Config::get('constants.test.user.max_item') + 1, $response['total']);
    }

    /** @test */
    public function paginate_should_return_n_users()
    {
        $this->seed(UserSeeder::class);

        $response = $this->get(route('user.index'));

        $response->assertOk();

        $this->assertLessThanOrEqual(Config::get('constants.pagination.max_item'), count($response['data']));
    }

    /** @test */
    public function should_fetch_user_by_id()
    {
        $this->seed(UserSeeder::class);

        $user = User::inRandomOrder()->first();

        $response = $this->get(route('user.show', ['user' => $user->id]));

        $response->assertOk();

        $response->assertJson(['id' => $user->id]);
    }

    /** @test */
    public function a_user_can_be_added()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertOk();

        $this->assertCount(2, User::all());
    }

    /** @test */
    public function a_email_is_required()
    {

        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
            'email' => '',
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_valid_email_address_should_be_given()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
            'email' => 'abc',
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_role_id_is_required()
    {
        $user = User::factory()->state([
            'role_id' => null,
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_should_be_numeric()
    {
        $user = User::factory()->state([
            'role_id' => 'abc',
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_greater_than_zero()
    {
        $user = User::factory()->state([
            'role_id' => 0,
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_less_than_five()
    {
        $user = User::factory()->state([
            'role_id' => 10,
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('role_id');

    }

    /** @test */
    public function a_name_is_required()
    {
        $user = User::factory()->state([
            'name' => null,
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_password_is_required()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make();

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_user_can_be_updated()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make()->makeVisible('password');

        $this->post(route('user.store'), $user->toArray());

        $user = User::latest()->first();

        $tmpUser = User::factory()->state([
            'role_id' => Config::get('constants.roles.supplier'),
        ])->make();

        $response = $this->put(route('user.update', ['user' => $user->id]), $tmpUser->toArray());

        $response->assertOk();

        $updated_user = User::latest()->first();

        $this->assertEquals($tmpUser->name, $updated_user->name);
        $this->assertEquals($tmpUser->email, $updated_user->email);
        $this->assertEquals($tmpUser->role_id, $updated_user->role_id);
    }

    /** @test */
    public function a_user_can_be_deleted()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make()->makeVisible('password');

        $response = $this->post(route('user.store'), $user->toArray());

        $response->assertOk();

        $this->assertCount(2, User::all());

        $user = User::latest()->first();

        $this->delete(route('user.destroy', ['user' => $user->id]));

        $this->assertCount(1, User::all());
    }
}

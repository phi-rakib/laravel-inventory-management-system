<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        User::factory()->count(7)->state(new Sequence(
            fn($sequence) => ['role_id' => rand(1, 4)]
        ))->create();

        $response = $this->get('/api/user');

        $response->assertOk();

        $response->assertJsonCount(8, 'data');
    }

    /** @test */
    public function paginate_should_return_n_items()
    {
        User::factory()->state(new Sequence(
            fn($sequence) => ['role_id' => rand(1, 4)]
        ))->count(30)->create();

        $response = $this->get('/api/user');

        $response->assertOk();

        $response->assertJsonCount(10, 'data');
    }

    /** @test */
    public function a_user_can_be_added()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

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

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_valid_email_address_should_be_given()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
            'email' => 'abc',
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_role_id_is_required()
    {
        $user = User::factory()->state([
            'role_id' => null,
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_should_be_numeric()
    {
        $user = User::factory()->state([
            'role_id' => 'abc',
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_greater_than_zero()
    {
        $user = User::factory()->state([
            'role_id' => 0,
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_less_than_five()
    {
        $user = User::factory()->state([
            'role_id' => 10,
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('role_id');

    }

    /** @test */
    public function a_name_is_required()
    {
        $user = User::factory()->state([
            'name' => null,
        ])->make()->makeVisible('password');

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_password_is_required()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make();

        $response = $this->post('/api/user', $user->toArray());

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function a_user_can_be_updated()
    {
        $user = User::factory()->state([
            'role_id' => Config::get('constants.roles.customer'),
        ])->make()->makeVisible('password');

        $this->post('/api/user', $user->toArray());

        $user = User::latest()->first();

        $tmpUser = User::factory()->state([
            'role_id' => Config::get('constants.roles.supplier'),
        ])->make();

        $response = $this->put('/api/user/' . $user->id, $tmpUser->toArray());

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

        $response = $this->post('/api/user', $user->toArray());

        $response->assertOk();

        $this->assertCount(2, User::all());

        $user = User::latest()->first();

        $this->delete('/api/user/' . $user->id);

        $this->assertCount(1, User::all());
    }
}

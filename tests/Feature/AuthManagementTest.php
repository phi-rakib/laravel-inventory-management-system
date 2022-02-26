<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register_as_a_supplier()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()
            ->state([
                'role_id' => Config::get('constants.roles.supplier'),
            ])->make()->makeVisible('password');

        $response = $this->post('/api/user/registration', $user->toArray());
        $response->assertOk();

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_can_register_as_a_customer()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()
            ->state([
                'role_id' => Config::get('constants.roles.customer'),
            ])->make()->makeVisible('password');

        $response = $this->post('/api/user/registration', $user->toArray());
        $response->assertOk();

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_user_can_not_register_as_an_admin()
    {
        $user = User::factory()
            ->state([
                'role_id' => Config::get('constants.roles.admin'),
            ])->make()->makeVisible('password');

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function a_user_can_not_register_as_a_sales_person()
    {
        $user = User::factory()
            ->state([
                'role_id' => Config::get('constants.roles.salesperson'),
            ])->make()->makeVisible('password');

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function to_register_a_user_an_email_is_required()
    {
        $user = User::factory()->state(['email' => null])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function to_register_a_user_a_valid_email_address_is_required()
    {
        $user = User::factory()->state(['email' => 'abc'])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function to_register_a_user_an_password_is_required()
    {
        $user = User::factory()->state(['password' => null])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function to_register_a_user_role_id_is_required()
    {
        $user = User::factory()->state(['role_id' => null])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function to_register_a_user_role_id_should_be_numeric()
    {
        $user = User::factory()->state(['role_id' => 'abc'])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function to_register_a_user_minimum_role_id_should_three()
    {
        $user = User::factory()->state(['role_id' => 2])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function to_register_a_user_maximum_role_id_should_four()
    {
        $user = User::factory()->state(['role_id' => 5])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function to_register_a_user_a_name_is_required()
    {
        $user = User::factory()->state(['name' => null])->make();

        $response = $this->post('/api/user/registration', $user->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_registered_user_can_login()
    {
        $data = [
            'email' => 'admin@test.com',
            'password' => '123456',
        ];

        User::factory()->state($data)->create();

        $response = $this->post('/api/user/login', $data);

        $response->assertOk();

        $this->assertFalse(empty($response['token']));

        $this->assertGreaterThan(0, strlen($response['token']));
    }

    /** @test */
    public function check_invalid_login_by_giving_wrong_password()
    {
        $data = [
            'email' => 'admin@test.com',
            'password' => '123456',
        ];

        User::factory()->state($data)->create();

        $data['password'] = '12345';

        $response = $this->post('/api/user/login', $data);

        $response->assertUnauthorized();
    }

    /** @test */
    public function a_email_is_required_for_login()
    {
        $data = [
            'email' => null,
            'password' => '123456',
        ];

        $response = $this->post('/api/user/login', $data);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_should_be_valid_for_login()
    {
        $data = [
            'email' => 'abc',
            'password' => '123456',
        ];

        $response = $this->post('/api/user/login', $data);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_password_is_required_for_login()
    {
        $data = [
            'email' => 'abc',
            'password' => null,
        ];

        $response = $this->post('/api/user/login', $data);

        $response->assertSessionHasErrors('password');
    }
}

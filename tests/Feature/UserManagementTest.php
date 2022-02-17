<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_added()
    {
        $response = $this->post('/api/user', [
            'role_id' => Config::get('constants.roles.customer'),
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertOk();

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_email_is_required()
    {
        $response = $this->post('/api/user', [
            'role_id' => Config::get('constants.roles.customer'),
            'name' => 'rakib',
            'email' => '',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_valid_email_address_should_be_given()
    {
        $response = $this->post('/api/user', [
            'role_id' => Config::get('constants.roles.customer'),
            'name' => 'rakib',
            'email' => 'abc',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_role_id_is_required()
    {
        $response = $this->post('/api/user', [
            'role_id' => '',
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_should_be_numeric()
    {
        $response = $this->post('/api/user', [
            'role_id' => 'abc',
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_greater_than_zero()
    {
        $response = $this->post('/api/user', [
            'role_id' => 0,
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function role_id_is_less_than_five()
    {
        $response = $this->post('/api/user', [
            'role_id' => 10,
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function a_name_is_required()
    {
        $response = $this->post('/api/user', [
            'role_id' => 2,
            'name' => '',
            'email' => 'phi.rakib@gmail.com',
            'password' => 'abc',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_password_is_required()
    {
        $response = $this->post('/api/user', [
            'role_id' => 2,
            'name' => 'rakib',
            'email' => 'phi.rakib@gmail.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }
    

}

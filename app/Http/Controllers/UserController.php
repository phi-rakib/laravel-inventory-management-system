<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return User::paginate(Config::get('constants.pagination.max_item'));
    }

    public function store()
    {
        $data = request()->validate([
            'email' => 'required|email',
            'role_id' => 'required|numeric|min:1|max:4',
            'name' => 'required',
            'password' => 'required',
        ]);

        User::create($data);
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'email' => 'required|email',
            'role_id' => 'required|numeric|min:1|max:4',
            'name' => 'required',
        ]);

        $user->update($data);
    }

    public function destroy(User $user)
    {
        $user->delete();
    }
}

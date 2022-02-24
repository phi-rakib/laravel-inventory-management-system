<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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

    public function show(User $user)
    {
        return $user;
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update($request->validated());
    }

    public function destroy(User $user)
    {
        $user->delete();
    }
}

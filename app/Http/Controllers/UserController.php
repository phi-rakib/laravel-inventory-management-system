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

    public function show(User $user)
    {
        return $user;
    }

    public function store()
    {
        $data = request()->validate($this->requestValidationArray());

        User::create($data);
    }

    public function update(User $user)
    {
        $data = request()
            ->validate(array_filter(
                $this->requestValidationArray(),
                fn($item) => $item != 'password',
                ARRAY_FILTER_USE_KEY)
            );

        $user->update($data);
    }

    public function destroy(User $user)
    {
        $user->delete();
    }

    private function requestValidationArray()
    {
        return [
            'email' => 'required|email',
            'role_id' => 'required|numeric|min:1|max:4',
            'name' => 'required',
            'password' => 'required',
        ];
    }
}

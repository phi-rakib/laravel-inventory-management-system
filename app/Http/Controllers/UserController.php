<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
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
}

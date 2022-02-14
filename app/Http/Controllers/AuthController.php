<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $user = User::create($input);
        $token = $user->createToken('MyAuthApp')->plainTextToken;
        return ["token" => $token];
    }
}

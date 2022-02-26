<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role_id' => 'required|numeric|min:3|max:4',
        ]);

        $user = User::create($data);

        $user_agent = request()->header('User-Agent');

        $token = $user->createToken($user_agent)->plainTextToken;

        return ["token" => $token];
    }

    public function login(Request $request)
    {
        $input = $request->all();

        if (Auth::attempt($input)) {
            $user = User::where('email', $input['email'])->firstOrFail();

            $user_agent = $request->header('User-Agent');

            $token = $user->createToken($user_agent)->plainTextToken;

            return ["token" => $token];
        }

        return response()->json(
            ['message' => 'Invalid login details'],
            401
        );
    }
}

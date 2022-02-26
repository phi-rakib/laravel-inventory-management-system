<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return ["token" => $this->generateToken($user)];
    }

    public function login(Request $request)
    {
        $data = $request->all();

        if (Auth::attempt($data)) {
            $user = User::where('email', $data['email'])->firstOrFail();

            return ["token" => $this->generateToken($user)];
        }

        return response()->json(
            ['message' => 'Invalid login details'],
            401
        );
    }

    private function generateToken(User $user)
    {
        return $user
            ->createToken(request()->header('User-Agent'))
            ->plainTextToken;
    }
}

<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\IAuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements IAuthRepository
{
    public function login($data)
    {
        $token = '';

        if (Auth::attempt($data)) {
            $user = User::where('email', $data['email'])->firstOrFail();
            $token = $this->generateToken($user);
        }

        return ["token" => $token];
    }

    public function logout()
    {

    }

    public function registration($data)
    {
        $user = User::create($data);

        return ["token" => $this->generateToken($user)];
    }

    private function generateToken(User $user)
    {
        return $user
            ->createToken(request()->header('User-Agent'))
            ->plainTextToken;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\IResourceRepository;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    private $userRepository;

    public function __construct(IResourceRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($data)
    {
        $token = '';

        if (Auth::attempt($data)) {
            // $user = User::where('email', $data['email'])->firstOrFail();
            $user = $this->userRepository->findWhere('email', $data['email']);
            $token = $this->generateToken($user);
        }

        return ["token" => $token];
    }

    public function logout()
    {

    }

    public function registration($data)
    {
        // $user = User::create($data);

        $user = $this->userRepository->create($data);

        return ["token" => $this->generateToken($user)];
    }

    private function generateToken(User $user)
    {
        return $user
            ->createToken(request()->header('User-Agent'))
            ->plainTextToken;
    }
}

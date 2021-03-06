<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthServiceInterface;
use App\Repositories\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($data)
    {
        $token = '';

        if (Auth::attempt($data)) {
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

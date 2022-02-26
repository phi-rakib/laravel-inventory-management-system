<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Repositories\IAuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(IAuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function store(StoreUserRequest $request)
    {
        return $this->authRepository->registration($request->validated());
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authRepository->login($data);

        if (empty($result['token'])) {
            return response()->json(
                ['message' => 'Invalid login details'],
                401
            );
        }

        return $result;
    }
}

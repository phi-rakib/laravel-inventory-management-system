<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function store(StoreUserRequest $request)
    {
        return $this->authService->registration($request->validated());
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($data);

        if (empty($result['token'])) {
            return response()->json(
                ['message' => 'Invalid login details'],
                401
            );
        }

        return $result;
    }
}

<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function login($data);

    public function registration($data);

    public function logout();
}

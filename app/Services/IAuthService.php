<?php

namespace App\Services;

interface IAuthService
{
    public function login($data);

    public function registration($data);

    public function logout();
}

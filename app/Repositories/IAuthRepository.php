<?php

namespace App\Repositories;

interface IAuthRepository
{
    public function login($data);

    public function registration($data);

    public function logout();
}

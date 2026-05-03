<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository
    ){}

    public function register(array $data): User
    {
        return $this->authRepository->create($data);
    }

    public function login(string $email , string $password): ?User
    {
        $user = $this->authRepository->findByEmail($email);

        if(!$user || !Hash::check($password, $user->password)){
            return null;
        }



        return $user;
    }

}
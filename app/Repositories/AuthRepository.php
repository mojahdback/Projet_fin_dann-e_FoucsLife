<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email' , $email)->first();
    }


    
    public function create(array $date): User
    {
       return User::create([
        'name' => $date['name'],
        'email'  => $date['email'],
        'password' => bcrypt($date['password']),
        'role'  => 'user' ,
        ]);
    }

}
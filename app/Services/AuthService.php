<?php

namespace App\Services;


use App\Models\User;

class AuthService
{
    public function register(array $args): string
    {
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => bcrypt($args['password']),
        ]);

        // Logic for sending verification email or activation here

        return 'User registered successfully';
    }
}

<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function registerUser($name, $email, $password)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        $token = 'JWTAuth::fromUser($user)';

        return $token;
    }
}

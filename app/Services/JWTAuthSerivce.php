<?php

namespace App\Services;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JWTAuthSerivce
{
    public function generateToken($user)
    {
        return JWTAuth::fromUser($user);
    }

    public function refreshToken()
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function invalidateToken()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getUserFromToken()
    {
        return JWTAuth::parseToken()->authenticate();
    }
}

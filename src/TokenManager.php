<?php

namespace Urerued\UreruedToken;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class TokenManager
{
    const TOKEN_KEY = 'token_key';

    public function createToken()
    {
        $token = Str::random(40);
        Cache::put(self::TOKEN_KEY, $token, 3600);
        return $token;
    }


    public function getToken()
    {
        return Cache::get(self::TOKEN_KEY);
    }


    public function verifyToken($token)
    {
        $storedToken = Cache::get(self::TOKEN_KEY);
        return $storedToken && $storedToken === $token;
    }

    public function deleteToken()
    {
        Cache::forget(self::TOKEN_KEY);
    }
}

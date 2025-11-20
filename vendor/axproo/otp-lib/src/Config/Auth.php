<?php 
namespace Axproo\Otp\Config;

class Auth
{
    public string $jwtSecret;
    public string $jwtRefresh;
    public string $jwtExpire;

    public function __construct() {
        $this->jwtSecret    = getenv('JWT_SECRET');
        $this->jwtRefresh   = getenv('JWT_REFRESH_SECRET');
        $this->jwtExpire    = (int) (env('JWT_EXPIRE') ?: 86400);
    }
}
<?php 
namespace Axproo\Auth\Config;

class Auth
{
    public string $jwtSecret;
    public string $jwtRefresh;
    public int $jwtExpire;

    public function __construct() {
        $this->jwtSecret = getenv('JWT_SECRET');
        $this->jwtRefresh = getenv('JWT_REFRESH_SECRET');
        $this->jwtExpire = (int) (getenv('JWT_EXPIRE') ?: 3600);
    }
}
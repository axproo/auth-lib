<?php 
namespace Axproo\Auth;

class AuthService
{
    private TokenManager $tokenManager;

    public function __construct() {
        $this->tokenManager = new TokenManager();
    }

    public function login(array $userData) : string {
        return $this->tokenManager->generateToken($userData);
    }

    public function verify(string $token) : ?object {
        return $this->tokenManager->validateToken($token);
    }
}
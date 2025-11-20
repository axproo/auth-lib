<?php 
namespace Axproo\Otp\Libraries;

use Axproo\Otp\Config\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenManager
{
    private string $secret;
    private string $refresh;
    private int $expire;

    public function __construct(?string $secret = null, ?string $refresh = null, ?int $expire = null) {
        $config = new Auth();
        $this->secret = $secret ?? $config->jwtSecret;
        $this->refresh = $refresh ?? $config->jwtRefresh;
        $this->expire = $expire ?? $config->jwtExpire;
    }

    public function generateToken(array $payload = []) : string {
        $payload['iat'] = time();
        $payload['exp'] = $payload['iat'] + $this->expire;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function refreshToken(array $payload) : string {
        $payload['iat'] = time();
        $payload['exp'] = $payload['iat'] + $this->expire;
        
        return JWT::encode($payload, $this->refresh, 'HS256');
    }

    public function renewToken(string $token) : ?string {
        try {
            $decoded = JWT::decode($token, new Key($this->refresh, 'HS256'));
            $data = (array) $decoded;
            unset($data['iat'], $data['exp']);

            return $this->generateToken($data);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function validateToken(string $token) : ?object {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
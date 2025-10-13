<?php 
namespace Axproo\Auth;

use Axproo\Auth\Config\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenManager
{
    private string $secret;
    private string $refresh;
    private int $expire;

    public function __construct(?string $secret = null, ?string $refresh = null, ?int $expire = null) {
        // Si l'appelant passe les valeurs, on les utilise (DI);
        if ($secret !== null && $refresh !== null && $expire !== null) {
            $this->secret = $secret;
            $this->refresh = $refresh;
            $this->expire = $expire;
            return;
        }

        $config = new Auth();
        $this->secret = $config->jwtSecret;
        $this->refresh = $config->jwtRefresh;
        $this->expire = $config->jwtExpire;
    }

    public function generateToken(array $payload) : string {
        $payload['iat'] = time();
        $payload['exp'] = $payload['iat'] + $this->expire;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function generateRefreshToken(array $payload) : string {
        $payload['iat'] = time();
        $payload['exp'] = $payload['iat'] + $this->expire;
        return JWT::encode($payload, $this->refresh, 'HS256');
    }

    public function renewToken(string $refreshToken) : ?string {
        try {
            $decoded = JWT::decode($refreshToken, new Key($this->refresh, 'HS256'));
            $data = (array) $decoded;
            unset($data['iat'], $data['exp']);
            return $this->generateToken($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function validateToken(string $token) : ?object {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage() . PHP_EOL;
            return null;
        }
    }
}
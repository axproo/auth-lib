<?php

use PHPUnit\Framework\TestCase;
use Axproo\Auth\Services\TokenManager;

final class AuthTest extends TestCase
{
    private TokenManager $tokenManager;

    protected function setUp(): void
    {
        // Simulation de variables d’environnement
        putenv('JWT_SECRET=my-secret-key');
        putenv('JWT_REFRESH_SECRET=my-refresh-key');
        putenv('JWT_EXPIRE=3600');

        $this->tokenManager = new TokenManager();
    }

    public function testGenerateAndValidateToken(): void
    {
        $payload = ['user_id' => 123, 'email' => 'test@example.com'];
        $token = $this->tokenManager->generateToken($payload);

        $this->assertNotEmpty($token, 'Le token ne doit pas être vide.');

        $decoded = $this->tokenManager->validateToken($token);
        $this->assertNotNull($decoded, 'Le token doit être valide.');
        $this->assertEquals(123, $decoded->user_id);
    }

    public function testExpiredToken(): void
    {
        $payload = ['user_id' => 1, 'email' => 'expired@example.com'];
        $token = (new TokenManager(null, null, 1))->generateToken($payload);

        sleep(2); // attendre pour que le token expire

        $decoded = $this->tokenManager->validateToken($token);
        $this->assertNull($decoded, 'Le token expiré doit être invalide.');
    }
}

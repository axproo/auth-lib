<?php 
namespace Axproo\Auth\Services;

class PasswordHasher
{
    public function hash(string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }
}
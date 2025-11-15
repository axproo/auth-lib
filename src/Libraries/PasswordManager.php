<?php 

namespace Axproo\Auth\Libraries;

class PasswordManager
{
    public function password_hash(string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function password_verify(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }
}
<?php 

namespace Axproo\Auth\Libraries;

class PasswordManager
{
    public function hash_password(string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify_password(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }
}
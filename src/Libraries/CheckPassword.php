<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckPassword
{
    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        $password = $data['password'] ?? null;

        if (!$user || $password) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        // Password verification
        if (!password_verify($password, $user->password)) {
            throw new AuthException(lang('Auth.invalid_credential'), 402);
        }

        $data['password_checked'] = true;
        return $data;
    }
}
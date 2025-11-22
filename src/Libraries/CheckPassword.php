<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckPassword
{
    public function handle(array $data): array
    {
        // Si déjà validé avant, on skip
        if (!empty($data['skip_password_check']) || !empty($data['skip_logout_remote'])) {
            return $data;
        }

        $user = $data['user'] ?? null;
        $password = $data['password'] ?? null;

        if (!$user || !$password) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        // Password verification
        if (!password_verify($password, $user->password)) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        $data['password_checked'] = true;
        log_message("debug", "Step 3: CheckPassword");
        return $data;
    }
}

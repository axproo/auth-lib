<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckEmailVerified
{
    public function handle(array $data): array
    {
        // Si déjà validé avant, on skip
        if (!empty($data['skip_password_check'])) {
            return $data;
        }

        $user = $data['user'] ?? null;

        // Get $emailVerified
        $emailVerified = $user->email_verified ?? null;

        // Force email verification for all users
        $forceVerify = config('Auth')->forceEmailVerification ?? false;

        if ($forceVerify && !$this->toBool($emailVerified)) {
            throw new AuthException(lang('Email.not_verified'), 403, ['redirectTo' => '/verify-email']);
        }
        $data['email_checked'] = true;
        log_message("debug", "Step 4: CheckEmailVerified");
        return $data;
    }

    private function toBool($val): bool
    {
        if (\is_bool($val)) {
            return $val;
        }
        if (\is_int($val)) {
            return $val === 1;
        }
        if (\is_string($val)) {
            return \in_array(strtolower($val), ['1','true','yes'], true);
        }
        return false;
    }
}

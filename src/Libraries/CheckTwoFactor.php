<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckTwoFactor
{
    public function handle(array $data): array
    {
        // Si déjà validé avant, on skip
        if (!empty($data['skip_password_check']) || !empty($data['skip_logout_remote'])) {
            return $data;
        }

        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'));
        }

        $force2FA   = config('Auth')->force2FA ?? false;
        $user2FA    = $user->two_factor_enabled ?? false;

        if ($force2FA || $this->toBool($user2FA)) {
            session()->set('2fa_user_id', $user->id);
            session()->set('2fa_pending', true);

            throw new AuthException(lang('Auth.twofactor_required'), 403);
        } else {
            $data['two_factor_checked'] = true;
        }
        log_message("debug", "Step 6: CheckTwoFactor");
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

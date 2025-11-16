<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;

class CheckTwoFactor
{
    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) throw new AuthException(lang('Users.missing'));

        $force2FA = config('Auth')->force2FA ?? false;
        $user2FA = $user->two_factor_enabled ?? false;

        if ($force2FA || $this->toBool($user2FA)) {
            $data['requires_2FA'] = true;
            throw new AuthException(lang('Auth.twofactor_required'), 403, ['action' => '2fa_required']);
        }
        $data['two_factor_checked'] = true;
        return $data;
    }

    private function toBool($val) : bool {
        if (\is_bool($val)) return $val;
        if (\is_int($val)) return $val === 1;
        if (\is_string($val)) return in_array(strtolower($val), ['1','true','yes'], true);
        return false;
    }
}
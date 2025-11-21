<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckTwoFactorValidation
{
    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        $code = $data['two_factor_code'] ?? null;

        if (!empty($data['requires_2FA']) && empty($data['two_factor_checked'])) {
            if (!$code || !$this->verifyCode($user, $code)) {
                throw new AuthException(lang('Auth.twofactor_invalid'), 403);
            }

            $data['two_factor_checked'] = true;
            $data['two_factor_pending'] = false;
        }
        return $data;
    }

    private function verifyCode($user, string $code) : bool {
        $otp = new OtpService();
        $verify = $otp->verifyTotp($user->email, $code);

        return $verify;
    }
}
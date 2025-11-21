<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckTwoFactorValidation
{
    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        $code = $data['two_factor_code'] ?? null;

        if (!$user) throw new AuthException(lang('Users.missing'));

        // Vérifier le code si fourni
        if (!$code || !$this->verifyCode($user, $code)) {
            throw new AuthException(lang('Auth.twofactor_invalid'), 403);
        }
        
        $data['two_factor_checked'] = true;
        $data['two_factor_pending'] = false;

        log_message("debug", "Step 7: CheckTwoFactorValidation");
        return $data;
    }

    private function verifyCode($user, string $code) : bool {
        $otp = new OtpService();
        return $otp->verifyTotp($user->email, $code);
    }
}
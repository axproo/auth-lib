<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckTwoFactorValidation
{
    public function handle(array $data): array
    {
        if (!empty($data['two_factor_checked'])) return $data;

        $user = $data['user'] ?? null;
        $code = $data['code'] ?? null;

        if (!$user) {
            throw new AuthException(lang('Users.missing'));
        }

        // Vérifier le code si fourni
        if (!$code || !$this->verifyCode($user, $code)) {
            throw new AuthException(lang('Otp.failed'), 403, [
                'data' => $data
            ]);
        }

        $data['two_factor_checked'] = true;
        $data['two_factor_pending'] = false;

        log_message("debug", "Step 7: CheckTwoFactorValidation -> code vérifié");
        return $data;
    }

    private function verifyCode($user, string $code): bool
    {
        $otp = new OtpService();
        return $otp->verifyTotp($user->email, $code);
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

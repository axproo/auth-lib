<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckStatus
{
    protected OtpService $otp;

    public function __construct()
    {
        $this->otp = new OtpService();
    }

    public function handle(array $data): array
    {
        // Si déjà validé avant, on skip
        if (!empty($data['skip_password_check'])) {
            return $data;
        }

        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 500);
        }

        $status = $user->status ?? 'active';
        log_message("debug", "Step 2: CheckStatus");

        switch ($status) {
            case 'pending':
                $response = $this->otp->sendForEmail($user->email, [
                    'subject' => 'Votre code de vérification',
                    'body' => 'emails/active_account',
                    'ttl' => 300,
                    'name' => "{$user->first_name} {$user->last_name}"
                ]);

                throw new AuthException(lang('Account.pending'), 403, [
                    'redirectTo' => '/verify-email',
                    'data' => $response
                ]);

            case 'inactive':
                throw new AuthException(lang('Account.inactive'), 403);
            case 'blocked':
                throw new AuthException(lang('Account.blocked'), 403);
            case 'active':
            default:
                $data['user_status_checked'] = true;
                return $data;
        }
    }
}

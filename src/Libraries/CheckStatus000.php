<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckStatus000
{
    protected OtpService $otp;

    public function __construct()
    {
        $this->otp = new OtpService();
    }

    public function handle(array $data): array
    {
        log_message("debug", "Step 2: CheckStatus\n");
        // // Si déjà validé avant, on skip
        // if (!empty($data['skip_logout_remote']) || !empty($data['skip_password_check'])) {
        //     return $data;
        // }

        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 500, [
                'stop_here' => true
            ]);
        }

        $status = $user->status ?? 'active';

        switch ($status) {
            case 'pending':
                $response = $this->otp->sendForEmail($user->email, [
                    'subject' => 'Votre code de vérification',
                    'body' => 'emails/active_account',
                    'ttl' => 300,
                    'name' => "{$user->first_name} {$user->last_name}"
                ]);
                session()->set('user_id', $user->id);

                throw new AuthException(lang('Account.pending'), 403, [
                    'redirectTo' => '/verify-email',
                    'data' => $response,
                    'stop_here' => true
                ]);

            case 'inactive':
                throw new AuthException(lang('Account.inactive'), 403, [
                    'stop_here' => true
                ]);
            case 'blocked':
                throw new AuthException(lang('Account.blocked'), 403, [
                    'stop_here' => true
                ]);
            case 'active':
            default:
                log_message("debug", "End Step 2\n");
                $data['user_status_checked'] = true;
                return $data;
        }
    }
}

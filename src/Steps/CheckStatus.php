<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Otp\Services\OtpService;

class CheckStatus
{
    protected OtpService $otp;

    public function __construct() {
        $this->otp = new OtpService();
    }

    public function handle(array $data) : array {
        log_message("debug", "Step 2: CheckStatus");

        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 404, ['stop_here' => true]);
        }

        $status = $user->status ?? 'active';

        switch ($status) {
            case 'pending':
                $payload = [
                    'subject' => lang('Emails.subject'),
                    'body' => 'emails/active_account',
                    'ttl' => 300,
                    'name' => "{$user->first_name} {$user->last_name}"
                ];
                $response = $this->otp->sendForEmail($user->email, $payload);
                session()->set('session_user_id', $user->id);

                throw new AuthException(lang('Users.pending'), 403, [
                    'redirectTo' => '/verify-email',
                    'data' => $response,
                    'stop_here' => true,
                    'email' => $user->email
                ]);
            
            case 'inactive':
                throw new AuthException(lang('Users.inactive'), 403, ['stop_here' => true]);
            
            case 'blocked':
                throw new AuthException(lang('Users.blocked'), 403, ['stop_here' => true]);
            
            case 'active':
            default:
                $data['status_checked'] = true;
                log_message("debug", "End Step 2\n");
                return $data;
        }
    }
}
<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;

class CheckEmailVerified extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start CheckEmailVerified");

        $user = $this->getUserData($data);
        $emailVerified = $user->email_verified ?? null;

        // Force email verification for all users
        $forceVerify = config('Auth')->forceEmailVerification ?? false;

        if ($forceVerify && !$this->convertToBool($emailVerified)) {
            throw new AuthException(lang('Emails.not_verified'), 403, [
                'redirectTo' => '/verify-email'
            ]);
        }
        $data['email_checked'] = true;
        log_message("debug", "End CheckEmailVerified\n");
        return $data;
    }
}
<?php 

namespace Axproo\Auth\Steps;

class CheckTwoFactorValidate extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start CheckTwoFactorValidate");

        $user = $this->getUserData($data);
        $code = $data['code'] ?? null;

        // Check if code OTP exist
        $this->verifyCode($user->email, $code);

        $data['two_factor_checked'] = true;
        $data['two_factor_pending'] = false;

        log_message("debug", "End CheckTwoFactorValidate");
        return $data;
    }
}
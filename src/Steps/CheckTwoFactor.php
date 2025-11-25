<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;

class CheckTwoFactor extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start CheckTwoFactor");
        // Récupérer les donnée de l'utilisateur
        $user = $this->getUserData($data);

        $force2FA = config('Auth')->force2FA ?? false;
        $user2FA = $user->two_factor_enabled ?? false;

        if ($force2FA || $this->convertToBool($user2FA)) {
            session()->set('session_user_id', $user->id);
            throw new AuthException(lang('Otp.twofactor_required'), 403, [
                'redirectTo' => '/2FA'
            ]);
        } else {
            $data['two_factor_checked'] = true;
        }
        log_message("debug", "End CheckTwoFactor\n");
        return $data;
    }
}
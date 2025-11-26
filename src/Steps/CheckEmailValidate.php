<?php

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use CodeIgniter\I18n\Time;

class CheckEmailValidate extends BaseStep
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(array $data): array
    {
        log_message("debug", "Start CheckEmailValid");

        $user = $this->getUserData($data);

        // Vérification de l'OTP
        $verified = $this->otp->verify($user->email, $data['code']);
        if (!$verified) {
            throw new AuthException(lang('Otp.invalid'), 400);
        }

        // Mise à jour de l'utilisateur
        $user->status = 'active';
        $user->email_verified = true;
        $user->email_verified_at = Time::now();

        $this->usersModel->save($user);

        log_message("debug", "End CheckEmailValid\n");
        return $data;
    }
}

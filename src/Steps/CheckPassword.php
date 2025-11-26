<?php

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;

class CheckPassword extends BaseStep
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(array $data): array
    {
        log_message("debug", "Start CheckPassword");

        $user = $this->getUserData($data);
        $password = $data['password'] ?? null;

        // Check the valid credential
        $this->validCredential($user, $password);

        // Check Password
        if (!password_verify($password, $user->password)) {
            throw new AuthException(lang('Users.invalid_credential'));
        }
        $data['password_checked'] = true;

        log_message("debug", "End CheckPassword\n");
        return $data;
    }
}

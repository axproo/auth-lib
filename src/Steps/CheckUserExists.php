<?php

namespace Axproo\Auth\Steps;

class CheckUserExists extends BaseStep
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(array $data): array
    {
        log_message('debug', 'Start CheckUserExists');

        $email = $data['email'] ?? null;
        $data['user'] = $this->getUserByEmail($email);
        $data['user_exist'] = true;

        log_message('debug', "End CheckUserExists\n");
        return $data;
    }
}

<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;

class CheckUserExists extends BaseStep
{
    public function handle(array $data) : array {
        log_message('debug', 'Step 1: CheckUserExists');

        $email = $data['email'] ?? null;
        $data['user'] = $this->getUserByEmail($email);
        $data['user_exist'] = true;

        log_message('debug', "End Step 1\n");
        return $data;
    }
}
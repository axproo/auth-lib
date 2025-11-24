<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;

class CheckUserExists
{
    protected UsersModel $model;

    public function __construct() {
        $this->model = new UsersModel();
    }

    public function handle(array $data) : array {
        log_message('debug', 'Step 1: CheckUserExists');

        $email = $data['email'] ?? null;
        if (!$email) {
            throw new AuthException(lang('Emails.required'), 403);
        }

        $user = $this->model->where('email', $email)->first();
        if (!$user) {
            throw new AuthException(lang('Users.missing'));
        }

        $data['user'] = $user;
        $data['user_exist'] = true;
        log_message('debug', "End Step 1\n");

        return $data;
    }
}
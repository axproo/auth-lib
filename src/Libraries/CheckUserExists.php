<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;

class CheckUserExists
{
    protected UsersModel $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public function handle(array $data): array
    {
        $email = $data['email'] ?? null;
        if (!$email) {
            throw new AuthException(lang('Email.required'), 400, [
                'stop_here' => true,
            ]);
        }

        $user = $this->model->where('email', $email)->first();
        if (!$user) {
            throw new AuthException(lang('Users.invalid_credential'), 401, [
                'stop_here' => true
            ]);
        }
        $data['user'] = $user;
        log_message("debug", "Step 1: CheckUserExists");

        return $data;
        // if (!empty($data['skip_logout_remote'])) {
        //     return $data;
        // }

        // $email = $data['email'] ?? null;

        // if (!$email) {
        //     throw new AuthException(lang('Email.required'), 400);
        // }

        // $user = $this->model->where('email', $email)->first();

        // if (!$user) {
        //     throw new AuthException(lang('Auth.invalid_credential'), 401);
        // }
        // $data['user'] = $user;
        // log_message("debug", "Step 1: CheckUserExists");
        // return $data;
    }
}

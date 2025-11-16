<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;

class CheckUserExists
{
    protected UsersModel $model;

    public function __construct() {
        $this->model = new UsersModel();
    }

    public function handle(array $data) : array {
        $email = $data['email'] ?? null;

        if (!$email) {
            throw new AuthException(lang('Email.required'), 400);
        }

        $user = $this->model->where('email', $email)->first();
        return ['user' => $user];
    }
}
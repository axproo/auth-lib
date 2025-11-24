<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;

abstract class BaseStep
{
    protected UsersModel $usersModel;

    public function __construct() {
        $this->usersModel = new UsersModel();
    }

    protected function getUserByEmail(string $email) {
        $user = $this->usersModel->where('email', $email)->first();
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 404, ['stop_here' => true]);
        }
        return $user;
    }
}
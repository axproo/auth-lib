<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;

class CheckSessionAgent
{
    protected UsersModel $model;

    public function __construct() {
        $this->model = new UsersModel();
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        $existingSession = $user->current_sess_token;

        if (!empty($existingSession)) {
            throw new AuthException(lang('Account.is_connected'), 403);
        }
        return $data;
    }
}
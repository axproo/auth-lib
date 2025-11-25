<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\UserSessionService;
use CodeIgniter\I18n\Time;

class FinalizeLogin
{
    protected UsersModel $model;
    protected UserSessionService $session;

    public function __construct()
    {
        $this->model = new UsersModel();
        $this->session = new UserSessionService();
    }

    public function handle(array $data): array
    {
        $token = $data['token'] ?? null;
        $user = $data['user'] ?? null;

        if (!$user) {
            throw new AuthException(lang('Users.missing'), 500);
        }

        // Vérifier que le 2FA a été validé si nécessaire
        if (!empty($data['requires_2FA']) && empty($data['two_factor_checked'])) {
            return $data;
        }

        // update user
        $user->last_login_at  = Time::now();
        $user->ip_address     = $data['ip_address'] ?? null;

        $this->model->save($user);

        // Update last session
        $this->session->registerSession($token, $user);
        session()->remove('session_user_id');

        log_message("debug", "Step 8: FinalizeLogin");
        return [
            'redirectTo' => '/dasbhoard',
        ];
    }
}

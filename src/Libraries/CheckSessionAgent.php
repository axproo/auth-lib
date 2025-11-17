<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;

class CheckSessionAgent
{
    protected UsersModel $model;
    protected TokenManager $token;
    protected UserSessionService $session;
    protected $request;

    public function __construct() {
        $this->model = new UsersModel();
        $this->token = new TokenManager();
        $this->session = new UserSessionService();
        $this->request = session('request');
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        // $token = $this->token->validateToken($this->request->getCookie('jwt'));
        // $existingSession = $this->session->validateSession($user->id, $token);
        $existingSession = '';

        if (!empty($existingSession)) {
            throw new AuthException(lang('Session.is_connected'), 403);
        }
        return $data;
    }
}
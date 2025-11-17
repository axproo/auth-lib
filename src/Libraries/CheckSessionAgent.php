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
    protected TenantManager $tenant;
    protected RoleManager $rules;
    protected $request;

    public function __construct() {
        $this->model = new UsersModel();
        $this->token = new TokenManager();
        $this->session = new UserSessionService();
        $this->tenant = new TenantManager();
        $this->rules = new RoleManager();
        $this->request = session('request');
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        $token = $this->token->generateToken([
            'uid' => $user->id,
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'fullname' => "{$user->first_name} {$user->last_name}",
            'role' => $this->rules->getRoleById($user->id),
            'status' => $user->status,
            'two_factor_enabled' => filter_var($user->two_factor_enabled, FILTER_VALIDATE_BOOLEAN)
        ]);
        $this->session->setCookie($token);

        $existingSession = $this->session->validateSession($user->id, $token);

        if (!$existingSession) {
            throw new AuthException(lang('Session.is_connected'), 403);
        }

        // if (!empty($existingSession) || $existingSession === false) {
        //     throw new AuthException(lang('Session.is_connected'), 403);
        // }
        $data['token'] = $token;
        $data['session'] = $existingSession;
        return $data;
    }
}
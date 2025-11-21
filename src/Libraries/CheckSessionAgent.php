<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\RoleModel;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;

class CheckSessionAgent
{
    protected UsersModel $model;
    protected TokenManager $token;
    protected UserSessionService $session;
    protected TenantManager $tenant;
    protected RoleModel $rules;
    protected $request;

    public function __construct() {
        $this->model = new UsersModel();
        $this->token = new TokenManager();
        $this->session = new UserSessionService();
        $this->tenant = new TenantManager();
        $this->rules = new RoleModel();
        $this->request = session('request');
    }

    public function handle(array $data) : array {
        // Si déjà validé avant, on skip
        if (!empty($data['skip_password_check'])) {
            return $data;
        }
        
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Auth.invalid_credential'), 401);
        }

        $token = $this->token->generateToken([
            'uid' => $user->id,
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'fullname' => "{$user->first_name} {$user->last_name}",
            'role' => $this->rules->findByUser($user->id),
            'status' => $user->status,
            'two_factor_enabled' => filter_var($user->two_factor_enabled, FILTER_VALIDATE_BOOLEAN)
        ]);

        $existingSession = $this->session->validateSession($user->id, $token);

        if (!$existingSession) {
            throw new AuthException(lang('Session.is_connected'), 403);
        }
        $this->session->setCookie($token);

        $data['token'] = $token;
        log_message("debug", "Step 5: CheckSessionAgent");
        return $data;
    }
}
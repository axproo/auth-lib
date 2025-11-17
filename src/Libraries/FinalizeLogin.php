<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;
use CodeIgniter\I18n\Time;

class FinalizeLogin
{
    protected TokenManager $token;
    protected UsersModel $model;
    protected TenantManager $tenant;
    protected RoleManager $rules;
    protected UserSessionService $session;

    public function __construct() {
        $this->token = new TokenManager();
        $this->model = new UsersModel();
        $this->tenant = new TenantManager();
        $this->rules = new RoleManager();
        $this->session = new UserSessionService();
    }

    public function handle(array $data) : array {
        $token = $data['token'] ?? null;
        $user = $data['user'] ?? null;
        if (!$user) throw new AuthException(lang('Users.missing'), 500);

        // $token = $this->token->generateToken([
        //     'uid' => $user->id,
        //     'tenant' => $this->tenant->getTenantById($user->id),
        //     'email' => $user->email,
        //     'fullname' => "{$user->first_name} {$user->last_name}",
        //     'role' => $this->rules->getRoleById($user->id),
        //     'status' => $user->status,
        //     'two_factor_enabled' => filter_var($user->two_factor_enabled, FILTER_VALIDATE_BOOLEAN)
        // ]);
        
        // update user
        $user->last_login_at  = Time::now();
        $user->ip_address     = $data['ip_address'] ?? null;

        $this->model->save($user);

        // Update last session
        $this->session->registerSession($token, $user);
        return [
            'data' => $data,
            'token' => $token,
            'redirect' => '/dasbhoard',
            'cookie' => $data['cookie'] ?? null
        ];
    }
}
<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;
use Axproo\Auth\Models\RoleModel;

class CheckLogoutRemote
{
    protected TokenManager $token;
    protected TenantManager $tenant;
    protected RoleModel $rules;
    protected UserSessionService $session;

    public function __construct() {
        $this->token = new TokenManager();
        $this->tenant = new TenantManager();
        $this->rules = new RoleModel();
        $this->session = new UserSessionService();
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        $this->session->terminateActiveSession($user->id);

        $token = $this->token->generateToken([
            'uid' => $user->id,
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'fullname' => "{$user->first_name} {$user->last_name}",
            'role' => $this->rules->findByUser($user->id),
            'status' => $user->status,
            'two_factor_enabled' => filter_var($user->two_factor_enabled, FILTER_VALIDATE_BOOLEAN)
        ]);
        
        $this->session->setCookie($token);

        $data['token'] = $token;
        log_message("debug", "Step 6: CheckLogoutRemote");
        return $data;
    }
}
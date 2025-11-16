<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Otp\Libraries\TokenManager;
use CodeIgniter\I18n\Time;

class FinalizeLogin
{
    protected TokenManager $token;
    protected UsersModel $model;
    protected TenantManager $tenant;
    protected RoleManager $rules;

    public function __construct() {
        $this->token = new TokenManager();
        $this->model = new UsersModel();
        $this->tenant = new TenantManager();
        $this->rules = new RoleManager();
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) throw new AuthException(lang('Users.missing'), 500);

        $token = $this->token->generateToken([
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'role' => $this->rules->getRoleById($user->id)
        ]);
        
        // update last connexion
        $user->last_login_at = Time::now();
        $user->ip_address = $data['ip_address'] ?? null;
        // $this->model->save($user);
        return [
            'user' => $user
        ];
    }
}
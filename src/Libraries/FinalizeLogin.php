<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Otp\Libraries\TokenManager;

class FinalizeLogin
{
    protected TokenManager $token;
    // protected UsersModel $model;
    protected TenantManager $tenant;
    protected RoleManager $rules;

    public function __construct() {
        $this->token = new TokenManager();
        // $this->model = new UsersModel();
        $this->tenant = new TenantManager();
        $this->rules = new RoleManager();
    }

    public function handle(array $data) : array {
        $user = $data['user'] ?? null;
        if (!$user) throw new AuthException(lang('Users.missing'), 500);

        // $token = $this->token->generateToken([
        //     'tenant' => $this->tenant->getTenantById($user['id']),
        //     'email' => $user['email'],
        //     'role' => $this->rules->getRoleById($user['id']),
        //     // 'redirect' => ''
        // ]);


        // // $token = $this->token->generateToken([
        // //     'tenant' => $this->tenant->getTenantById($user->id),
        // //     'email' => $user->email,
        // //     'role' => $this->rules->getRoleById($user->role_id),
        // //     'redirect' => $this->redirectTo($user)
        // // ]);
        return $data;
    }
}
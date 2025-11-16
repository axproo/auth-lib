<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Libraries\PasswordManager;
use Axproo\Auth\Libraries\RoleManager;
use Axproo\Auth\Libraries\TenantManager;
use Axproo\Auth\Models\UsersModel;
use Axproo\Otp\Libraries\TokenManager;
use Config\Services;

abstract class BaseAuthService
{
    // protected $model;
    protected $request;
    protected $validation;
    // protected TokenManager $token;
    // protected PasswordManager $hasher;
    // protected TenantManager $tenant;
    // protected RoleManager $rules;

    public function __construct() {
        // $this->model = new UsersModel();
        // $this->hasher = new PasswordManager();
        // $this->token = new TokenManager();
        // $this->tenant = new TenantManager();
        // $this->rules = new RoleManager();
        $this->request = service('request');
        $this->validation = Services::validation();
    }

    protected function validate(array $rules) : bool {
        if (!$this->validation->setRules($rules)->run($this->get_data_from_post())) {
            return false;
        }
        return true;
    }

    // protected function respondSuccess(string $message, array $data = []) {
    //     return axprooResponse(200, $message, $data);
    // }

    // protected function respondError(string|array $message, int $code = 403, array $data = []) {
    //     return axprooResponse($code, $message, $data);
    // }

    // protected function checkStatus($user) {
    //     switch ($user->status) {
    //         case 'active': return true;
    //         case 'pending': return '/verify-email';
    //         case 'blocked': throw new \Exception("Account.blocked");
    //         case 'inactive': throw new \Exception(lang('Account.inactive'));
    //         default: throw new \Exception(lang('Status.unknown', ['status' => $user->status]));
    //     }
    // }

    protected function get_data_from_post() {
        return (array) $this->request->getVar();
    }
}
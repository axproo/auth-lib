<?php
namespace Axproo\Auth\Services;

use Axproo\Auth\Configs\Validation\AuthConfig;
use Axproo\Auth\Models\RuleModel;
use Axproo\Auth\Models\UserModel;
use Config\Services;

class AuthService
{
    protected $validation;
    protected $validate;

    protected UserModel $users;
    protected RuleModel $roles;
    protected PasswordHasher $hasher;
    protected TokenManager $token;
    protected UserManager $account;

    public function __construct() {
        $this->validation = Services::validation();
        $this->validate = new AuthConfig;

        $this->users = new UserModel();
        $this->hasher = new PasswordHasher();
        $this->roles = new RuleModel();
        $this->token = new TokenManager();
        $this->account = new UserManager();
    }

    public function login(array $data = []) {
        $rules = $this->validate->auth;
        
        // Validation du formulaire
        if (!$this->validation->setRules($rules)->run($data)) {
            return axprooResponse(403, $this->validation->getErrors());
        }

        // Vérification du status du user
        $user = $this->users->findByEmail($data['email']);
        $statusCheck = $this->account->getStatus($user->status);

        if (is_array($statusCheck)) {
            return axprooResponse(403, lang('Auth.failed.account.verify', $statusCheck));
        }

        // Vérification du mot de passe
        if (!$this->hasher->verify($data['password'], $user->password)) {
            return axprooResponse(500, lang('Auth.failed.password.incorrect'));
        }

        $overrides = [];
        if(!empty($user->totp_secret)) $overrides['twofa_pending'] = true;

        // Généré le token
        $userData = array_merge([
            'tenant_id'     => '',
            'role'          => $this->roles->findByUser($user->role_id)
        ], $this->account->getUserAccount($user, $overrides));
        $token = $this->token->generateToken($userData);

        // Authentification à 2 facteurs 2FA
        if ($userData['twofa_pending'] === true) {
            return axprooResponse(401, lang('Auth.failed.twofactor_need'), [
                'redirect' => '/2FA',
                'token' => $token
            ]);
        }
        $this->account->setCookie($token, getenv('JWT_EXPIRE'));

        return axprooResponse(200, 'Success', [
            'redirect'  => '/dashboard',
        ]);
    }
}
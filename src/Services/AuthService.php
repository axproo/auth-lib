<?php
namespace Axproo\Auth\Services;

use Axproo\Auth\Configs\Validation\AuthConfig;
use Axproo\Auth\Models\RuleModel;
use Axproo\Auth\Models\UserModel;

class AuthService extends BaseService
{
    protected $validate;

    protected UserModel $users;
    protected RuleModel $roles;
    protected PasswordHasher $hasher;
    protected TokenManager $token;
    protected UserManager $account;
    protected TenantService $tenant;

    public function __construct() {
        parent::__construct();
        $this->validate = new AuthConfig;

        $this->users = new UserModel();
        $this->hasher = new PasswordHasher();
        $this->roles = new RuleModel();
        $this->token = new TokenManager();
        $this->account = new UserManager();
        $this->tenant = new TenantService();
    }

    public function login(array $data = []) {
        $data = $this->get_data_from_post();
        
        // Validation du formulaire
        if (!$this->validate($this->validate->auth)) {
            return $this->respondError($this->validation->getErrors());
        }

        // Vérification du status du user
        $user = $this->users->findByEmail($data['email']);
        $statusCheck = $this->account->getStatus($user->status);

        if (is_array($statusCheck)) {
            return $this->respondError(lang('Auth.failed.account.verify', $statusCheck));
        }

        // Vérification du mot de passe
        if (!$this->hasher->verify($data['password'], $user->password)) {
            return $this->respondError(lang('Auth.failed.password.incorrect'));
        }

        $overrides = [];
        if(!empty($user->totp_secret)) $overrides['twofa_pending'] = true;

        // Généré le token
        $userData = array_merge([
            'tenant_id'     => $this->tenant->getUserTenant($user->id),
            'role'          => $this->roles->findByUser($user->role_id)
        ], $this->account->getUserAccount($user, $overrides));
        $token = $this->token->generateToken($userData);

        // Authentification à 2 facteurs 2FA
        if ($userData['twofa_pending'] === true) {
            return $this->respondError(lang('Auth.failed.twofactor_need'), 401, [
                'redirect' => '/2FA',
                'token' => $token
            ]);
        }

        // Enregistrement du cookie
        $this->account->setCookie($token, 86400);

        return axprooResponse(200, 'Success', [
            'redirect'  => '/dashboard',
            'user' => $user
        ]);
    }

    public function validateToken(string $token) {
        return $this->token->validateToken($token);
    }
}
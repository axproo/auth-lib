<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Config\Validation\AuthConfig;

class AuthService extends BaseAuthService
{
    protected $valid;

    public function __construct() {
        parent::__construct();
        $this->valid = new AuthConfig;
    }

    public function login() {
        $data = $this->get_data_from_post();

        // Validation des données
        if (!$this->validate($this->valid->auth)) {
            return $this->respondError($this->validation->getErrors());
        }

        // Vérification du status et du mot de passe de l'utilisateur
        $user = $this->model->findByEmail($data['email']);

        if (!$this->hasher->verify_password($data['password'], $user->password)) {
            return $this->respondError(lang(line: 'Password.incorrect'));
        }

        // $checkStatus = 

        $token = $this->token->generateToken([
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'role' => $this->rules->getRoleById($user->role_id),
            'redirect' => $this->redirectTo($user->status)
        ]);

        return $this->respondSuccess('Success connexion', [
            'token' => $token,
            'redirect' => $this->redirectTo($user->status, $token)
        ]);
    }
}
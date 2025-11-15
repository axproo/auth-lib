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
        return $this->respondSuccess('Success connexion', [
            'data' => $data
        ]);
    }
}
<?php 

namespace Axproo\Auth\Services;

class AuthService extends BaseAuthService
{
    public function __construct() {
        parent::__construct();
    }

    public function login() {
        $data = $this->get_data_from_post();

        // Validation du mot de passe
        return $this->respondSuccess('Success connexion', [
            'data' => $data
        ]);
    }
}
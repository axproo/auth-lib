<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Config\Validation\AuthConfig;
use Axproo\Auth\Services\AuthService;
use Config\Services;

class CheckUserExists extends BaseLib
{
    protected AuthConfig $validate;

    public function __construct() {
        $this->validate = new AuthConfig;
    }

    public function handle(array $data) : array {
        $rules = $this->validate->auth;

        // Validation des données
        // if (!$this->validation->setRules($rules)->run($data)) {
        //     return 
        // }
        return ['check user' => $data];
    }
}
<?php 

namespace Axproo\Auth\Config\Validation;

use CodeIgniter\Config\BaseConfig;

class AuthConfig extends BaseConfig
{
    public array $auth = [];
    public array $code = [];

    protected array $baseDefinitions = [];

    public function __construct() {
        $this->baseDefinitions = [
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required'      => lang('Auth.failed.email.required'),
                    'valid_email'   => lang('Auth.failed.email.valid_email'),
                    'is_not_unique' => lang('Auth.failed.email.incorrect')
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required'  => lang('Auth.failed.password.required'),
                    'min_length' => lang('Auth.failed.password.length')
                ]
            ],
            'code' => [
                'rules' => 'required|numeric|min_length[6]|max_length[6]',
                'errors' => [
                    'required'  => lang('Otp.required'),
                    'min_length' => lang('Otp.invalid'),
                    'max_length' => lang('Otp.invalid')
                ]
            ]
        ];
        $this->auth = $this->render(['email','password']);
        $this->code = $this->render(['email', 'code']);
    }

    public function render(array $fields) : array {
        $rules = [];

        foreach ($fields as $field) {
            if (isset($this->baseDefinitions[$field])) {
                $rules[$field] = $this->baseDefinitions[$field];
            }
        }
        return $rules;
    }
}
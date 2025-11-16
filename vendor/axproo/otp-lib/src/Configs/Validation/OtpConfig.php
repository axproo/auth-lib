<?php 
namespace Axproo\Otp\Configs\Validation;

use CodeIgniter\Config\BaseConfig;

class OtpConfig extends BaseConfig
{
    public array $otp_code = [];

    protected array $baseDefinitions = [];

    public function __construct() {
        $this->baseDefinitions = [
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required'      => lang('Email.required'),
                    'valid_email'   => lang('Email.valid_email'),
                    'is_not_unique' => lang('Email.invalid')
                ]
            ],
            'code' => [
                'rules' => 'required|numeric|min_length[6]|max_length[6]',
                'errors' => [
                    'required'   => lang('Otp.required'),
                    'min_length' => lang('Otp.invalid'),
                    'max_length' => lang('Otp.invalid')
                ]
            ]
        ];
        $this->otp_code = $this->render(fields: ['code']);
    }

    public function render(array $fields = []): array {
        $rules = [];
        
        foreach ($fields as $field) {
            if (isset($this->baseDefinitions[$field])) {
                $rules[$field] = $this->baseDefinitions[$field];
            }
        }
        return $rules;
    }
}
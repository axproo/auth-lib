<?php 

namespace Axproo\Auth\Services;

use Axproo\Otp\Libraries\TokenManager;
use Axproo\Otp\Services\OtpService;
use Config\Services;

abstract class BaseAuthService
{
    protected $request;
    protected $response;
    protected $validation;
    protected TokenManager $token;
    protected OtpService $otp;

    public function __construct() {
        $this->request = service('request');
        $this->response = service('response');
        $this->validation = Services::validation();
        $this->token = new TokenManager();
        $this->otp = new OtpService();
    }

    protected function validate(array $rules) : bool {
        if (!$this->validation->setRules($rules)->run($this->get_data_from_post())) {
            return false;
        }
        return true;
    }

    protected function respondSuccess(string $message, array $data = []) {
        return axprooResponse(200, $message, $data);
    }

    protected function respondError(string|array $message, int $code = 403, array $data = []) {
        return axprooResponse($code, $message, $data);
    }

    protected function get_data_from_post() {
        return (array) $this->request->getVar();
    }
}
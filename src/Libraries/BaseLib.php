<?php 

namespace Axproo\Auth\Libraries;

use Config\Services;

abstract class BaseLib
{
    protected $validation;

    public function __construct() {
        $this->validation = Services::validation();
    }

    protected function respondSuccess(string $message, array $data = []) {
        return axprooResponse(200, $message, $data);
    }

    protected function respondError(string|array $message, int $code = 403, array $data = []) {
        return axprooResponse($code, $message, $data);
    }
}
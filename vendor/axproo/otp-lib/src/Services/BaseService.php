<?php 
namespace Axproo\Otp\Services;

use Axproo\Otp\Libraries\TokenManager;
use Axproo\Otp\Models\OtpModel;
use CodeIgniter\Config\Services;
use CodeIgniter\I18n\Time;

abstract class BaseService
{
    protected $model;
    protected $request;
    protected $validation;
    protected $token;

    public function __construct() {
        $this->request = service('request');
        $this->validation = Services::validation();
        $this->model = new OtpModel();
        $this->token = new TokenManager();
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

    protected function respondError(string|array $message, ?int $code = 403, array $data = []) {
        return axprooResponse($code, $message, $data);
    }

    protected function create_otp($userId, $purpose, $target, $time) {
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Time::now()->addMinutes($time);

        $code = [
            'user_id' => $userId,
            'code' => $code,
            'purpose' => $purpose,
            'target' => $target,
            'expires_at' => $expiresAt
        ];
        return $code;
    }

    protected function check_otp(?int $userId) {
        $otp = $this->model->where([
            'user_id' => $userId ?? null,
            'code' => $this->request->getVar('code'),
            'purpose' => $this->request->getVar('purpose'),
            'target' => $this->request->getVar('target'),
            'is_used' => false
        ])->first();

        if (!$otp) throw new \Exception(lang('Otp.not_found'));
        if (Time::now()->isAfter($otp->expires_at)) throw new \Exception("Otp.invalid");
        
        $otp->is_used = true;
        $this->model->save($otp);

        return true;
    }

    protected function redirectTo($purpose) {
        $baseRedirect = [
            'login'         => '/login',
            'email_verify'  => '/verify-email'
        ];
        return $baseRedirect[$purpose];
    }

    protected function get_data_from_post() : array {
        return (array) $this->request->getVar();
    }
}
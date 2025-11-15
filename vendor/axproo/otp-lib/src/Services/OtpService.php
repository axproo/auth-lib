<?php 
namespace Axproo\Otp\Services;

use Axproo\Otp\Configs\Validation\OtpConfig;
use CodeIgniter\I18n\Time;

class OtpService extends BaseService
{
    protected $validateData;

    public function __construct() {
        parent::__construct();
        $this->validateData = new OtpConfig();
    }

    public function generate(?int $time = 5) {
        $data = $this->get_data_from_post();

        $target = $data['target'] ?? null;
        $purpose = $data['purpose'] ?? 'login';

        if (!$data || !$data['target']) {
            return $this->respondError(lang('Otp.target.failed'));
        }

        // Vérification du status user
        $user = $this->model->findUserByEmail($data['email']);
        $code = $this->create_otp($user->id, $purpose, $target, $time);

        $user->code = $code['code'];

        // Send OTP by Email or Phone
        $sent = [];

        return $this->respondSuccess(lang('Email.send'), [
            'email' => $user->email,
            'redirect' => $this->redirectTo($code['purpose']) ?? '/',
            'code' => $code,
            'data' => $sent
        ]);
    }

    public function verify() {
        $token = $this->request->getCookie('jwt');

        if (!$token) {
            return $this->respondError(lang(line: 'Token.failed'));
        }
        $user = $this->token->validateToken($token);

        // Validation des données
        if (!$this->validate($this->validateData->otp_code)) {
            return $this->respondError($this->validation->getErrors());
        }

        // Vérification du status user
        $data = $this->model->findUserByEmail($user->email);
        $this->check_otp($data->id);

        $data->status = 'active';
        $data->email_verified = true;
        $data->email_verified_at = Time::now();

        $this->model->update_user($data);
        return $this->respondSuccess('Success', [
            'data' => $data,
        ]);
    }
}
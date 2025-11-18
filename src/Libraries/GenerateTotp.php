<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\AccessService;
use Axproo\Otp\Services\OtpService;

class GenerateTotp
{
    protected UsersModel $model;
    protected OtpService $totp;

    public function __construct() {
        $this->model = new UsersModel();
        $this->totp = new OtpService();
    }

    public function handle() {
        $id = AccessService::uid();
        $user = $this->model->find($id);

        if (!$user) {
            throw new AuthException(lang('Users.missing'), 400);
        }

        if (!empty($user->totp_secret)) {
            throw new AuthException(lang('Totp.is_generate'));
        }
        $totp = $this->totp->generateTotp($user->email);
        $user->totp_secret = $totp['secret'];
        $user->totp_uri = $totp['uri'];
        
        $this->model->save($user);
    }
}
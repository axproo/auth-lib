<?php

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Otp\Services\OtpService;
use CodeIgniter\I18n\Time;

class ValidEmailVerified
{
    protected UsersModel $model;
    protected OtpService $otp;

    public function __construct()
    {
        $this->model = new UsersModel();
        $this->otp = new OtpService();
    }

    public function handle(array $data): array
    {
        $email = $data['email' ?? null];
        $user = $this->model->where('email', $email)->first();

        if (!$user) {
            throw new AuthException(lang('Users.missing'));
        }

        $verified = $this->otp->verify($user->email, $data['code']);

        if (!$verified) {
            throw new AuthException(lang('Otp.invalid'), 400);
        }
        $user->status = 'active';
        $user->email_verified = true;
        $user->email_verified_at = Time::now();

        $this->model->save($user);
        return ['redirectTo' => '/login'];
    }
}

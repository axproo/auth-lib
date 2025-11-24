<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\AuthService;
use Axproo\Otp\Services\OtpService;
use CodeIgniter\I18n\Time;

class CheckEmailValid
{
    protected OtpService $otp;
    protected UsersModel $model;

    public function __construct() {
        $this->otp = new OtpService();
        $this->model = new UsersModel();
    }

    public function handle(array $data) : array {
        log_message("debug", "Step 5: CheckEmailValid");

        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 500, [
                'stop_here' => true
            ]);
        }

        $verified = $this->otp->verify($user->email, $data['code']);
        if (!$verified) {
            throw new AuthException(lang('Otp.invalid'), 400, [
                'stop_here' => true
            ]);
        }

        $user->status = 'active';
        $user->email_verified = true;
        $user->email_verified_at = Time::now();

        $this->model->save($user);
        log_message("debug", "End Step 5\n");
        return $data;
        // $email = $data['email' ?? null];
        // $user = $this->model->where('email', $email)->first();

        // if (!$user) {
        //     throw new AuthException(lang('Users.missing'));
        // }

        // $verified = $this->otp->verify($user->email, $data['code']);

        // if (!$verified) {
        //     throw new AuthException(lang('Otp.invalid'), 400);
        // }
        // $user->status = 'active';
        // $user->email_verified = true;
        // $user->email_verified_at = Time::now();

        // $this->model->save($user);
        // return ['redirectTo' => '/login'];
    }
}
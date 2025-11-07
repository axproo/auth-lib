<?php 
namespace Axproo\Auth\Libraries;

use Axproo\Auth\Models\OtpModel;
use CodeIgniter\I18n\Time;

class OtpManager
{
    protected OtpModel $model;

    public function __construct() {
        $this->model = new OtpModel();
    }

    public function generate($userId, int $time = 15) {
        $code = generateCode();
        $this->delete_otp($userId);

        $this->model->save([
            'user_id' => $userId,
            'otp_code' => $code,
            'expires_at' => generateTime($time)
        ]);
        return $code;
    }

    public function check_otp($userId, $code) {
        $otp = $this->model->where([
            'user_id' => $userId,
            'otp_code' => $code
        ])->first();

        if (!$otp) throw new \Exception(lang('Auth.failed.otp.not_found'));
        if (Time::now()->isAfter($otp->expires_at)) throw new \Exception(lang('Auth.failed.otp.invalid'));

        return $otp;
    }

    public function resend_otp(?int $userId, ?int $delay = 5) {
        $RESEND_DELAY = $delay * 60;
        // Récuperer le dernier OTP envoyé à l'utilisateur
        $last_otp = $this->model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();
        if (!$last_otp) return true;

        $elapsed = Time::now()->getTimestamp() - Time::parse($last_otp->created_at)->getTimestamp();
        return $elapsed >= $RESEND_DELAY;
    }

    public function delete_otp($userId) {
        $this->model->where('user_id', $userId)->delete();
    }
}
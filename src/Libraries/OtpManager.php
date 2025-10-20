<?php 
namespace Axproo\Auth\Libraries;

use Axproo\Auth\Models\OtpModel;

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

    protected function delete_otp($userId) {
        $this->model->where('user_id', $userId)->delete();
    }
}
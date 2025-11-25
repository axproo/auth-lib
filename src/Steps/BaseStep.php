<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Models\UsersModel;
use Axproo\Otp\Services\OtpService;

abstract class BaseStep
{
    protected UsersModel $usersModel;
    protected OtpService $otp;

    public function __construct() {
        $this->usersModel = new UsersModel();
        $this->otp = new OtpService();
    }

    protected function getUserData(array $data) {
        $user = $data['user'] ?? null;
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 404, [
                'stop_here' => true,
                'method' => 'getUserData()',
                'data' => $data
            ]);
        }
        return $user;
    }

    protected function getUserByEmail(string $email) {
        $user = $this->usersModel->where('email', $email)->first();
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 404, ['stop_here' => true]);
        }
        return $user;
    }

    protected function toBool($val) : bool {
        if (\is_bool($val)) return $val;
        if (\is_int($val)) return $val === 1;
        if (\is_string($val)) return \in_array(strtolower($val), ['1','true','yes'], true);
        
        return false;
    }
}
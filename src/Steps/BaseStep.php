<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Libraries\TenantManager;
use Axproo\Auth\Models\RoleModel;
use Axproo\Auth\Models\UsersModel;
use Axproo\Auth\Services\UserSessionService;
use Axproo\Otp\Libraries\TokenManager;
use Axproo\Otp\Services\OtpService;

abstract class BaseStep
{
    protected UsersModel $usersModel;
    protected OtpService $otp;
    protected TokenManager $token;
    protected UserSessionService $session;
    protected TenantManager $tenant;
    protected RoleModel $rules;

    public function __construct() {
        $this->usersModel = new UsersModel();
        $this->otp = new OtpService();
        $this->token = new TokenManager();
        $this->session = new UserSessionService();
        $this->tenant = new TenantManager();
        $this->rules = new RoleModel();
    }

    protected function validCredential(array $user, $password) {
        if (!$user || !$password) {
            throw new AuthException(lang('Users.invalid_credential'));
        }
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

    protected function validateSession($userId, $token) {
        $existingSession = $this->session->validateSession($userId, $token);

        if (!$existingSession) {
            // session()->set('session_user_id', )
            throw new AuthException(lang('Session.is_connected'), 403, [
                'redirectTo' => '/logout-remote'
            ]);
        }
        $this->session->setCookie($token);
    }

    // protected function toBool($val) : bool {
    //     if (\is_bool($val)) return $val;
    //     if (\is_int($val)) return $val === 1;
    //     if (\is_string($val)) return \in_array(strtolower($val), ['1','true','yes'], true);

    //     return false;
    // }

    protected function convertToBool($val) : bool {
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }
}
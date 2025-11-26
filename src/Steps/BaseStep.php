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

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->otp = new OtpService();
        $this->token = new TokenManager();
        $this->session = new UserSessionService();
        $this->tenant = new TenantManager();
        $this->rules = new RoleModel();
    }

    protected function validCredential(?object $user, $password)
    {
        if (!$user || !$password) {
            throw new AuthException(lang('Users.invalid_credential'));
        }
    }

    protected function verifyCode(string $email, ?int $code)
    {
        if (!$code || !$this->otp->verifyTotp($email, $code)) {
            throw new AuthException(lang('Otp.failed'), 403);
        }
    }

    protected function getUserData(array $data)
    {
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

    protected function getUserByEmail(string $email)
    {
        $user = $this->usersModel->where('email', $email)->first();
        if (!$user) {
            throw new AuthException(lang('Users.missing'), 404, ['stop_here' => true]);
        }
        return $user;
    }

    protected function generateToken(?object $user)
    {
        return $this->token->generateToken([
            'uid' => $user->id,
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'fullname' => "{$user->first_name} {$user->last_name}",
            'role' => $this->rules->findByUser($user->id),
            'status' => $user->status,
            'two_factor_enabled' => $this->convertToBool($user->two_factor_enabled)
        ]);
    }

    protected function validateSession($userId, $token)
    {
        $existingSession = $this->session->validateSession($userId, $token);

        if (!$existingSession) {
            session()->set('session_user_id', $userId);
            throw new AuthException(lang('Session.is_connected'), 403, [
                'redirectTo' => '/logout-remote'
            ]);
        }
        $this->setCookies($token);
    }

    protected function setCookies($token)
    {
        $this->session->setCookie($token);
    }

    protected function convertToBool($val): bool
    {
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }
}

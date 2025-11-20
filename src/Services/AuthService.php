<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Config\Validation\AuthConfig;
use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Libraries\AuthLib;
use Axproo\Auth\Libraries\GenerateTotp;
use Axproo\Auth\Libraries\ValidEmailVerified;

class AuthService extends BaseAuthService
{
    protected $valid;

    public function __construct() {
        parent::__construct();
        $this->valid = new AuthConfig;
    }

    public function login() {
        $payload = $this->get_data_from_post();
        $pipeline = new AuthLib();

        try {
            if (!$this->validate($this->valid->auth)) {
                return $this->respondError($this->validation->getErrors());
            }
            $payload['ip_address'] = $this->request->getIPAddress();

            $result = $pipeline->handle($payload);
            return axprooResponse(200, 'Successfully Login', $result);

        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;
            
            return axprooResponse($code, $e->getMessage(), $payload);
        } catch (\Throwable $t) {
            return axprooResponse(500, $t->getMessage());
        }
    }

    public function verifyEmail() {
        $payload = $this->get_data_from_post();
        $pipeline = new ValidEmailVerified();

        try {
            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }

            $result = $pipeline->handle($payload);
            return $this->respondSuccess(lang('Otp.verified'), $result);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 403);
        }
    }

    public function generateOtp() {
        $pipeline = new GenerateTotp();
        try {
            $result = $pipeline->handle();
            return $this->respondSuccess(lang('Totp.success.generated'), [
                'data' => $result
            ]);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 403);
        }
    }

    public function logout() {
        $session = new UserSessionService();
        
        try {
            $token = $session->getCookie('jwt');
            if (!$token) {
                return $this->respondError(lang('Session.destroy'), 403);
            }
            $delete = $session->destroySession($token);
            if (!$delete) return $this->respondError(lang('Session.not_delete'));

            $session->clearCookie();

            return $this->respondSuccess(lang('Session.disconnected'), [
                'redirectTo' => '/login'
            ]);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 500);
        }
    }
}
<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Config\Validation\AuthConfig;
use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Libraries\AuthLib;
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

            $result = [];
            return $this->respondSuccess(lang('Otp.verified'), $result);

            // $verified = $this->otp->verify($payload['email'], $payload['code']);
            // if (!$verified) {
            //     return $this->respondError(lang('Otp.invalid'), 400);
            // }

            
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 403);
        }
        // $email = $this->request->getVar('email') ?? null;
        // $code = $this->request->getVar('code') ?? null;

        // try {
        //     $verified = $this->otp->verify($email, $code);

        //     if (!$verified) {
        //         return $this->respondError(lang('Otp.invalid'), 400);
        //     }

        //     $user = $this->model->findByEmail($email);
        //     if (!$user) {
        //         return $this->respondError(lang('Users.missing'), 500);
        //     }

        //     // Mise à jour de l'utilisateur
        //     $user['status'] = 'active';
        //     $user['email_verified'] = true;
        //     $user['email_verified_at'] = Time::now();
        //     $user['last_login_at'] = Time::now();
        //     $user['ip_address'] = $this->request->getIPAddress();

        //     // Mise à jour de la session

        //     return $this->respondSuccess(lang('Otp.verified'), [
        //         'redirectTo' => '/'
        //     ]);
        // } catch (\Throwable $e) {
        //     return $this->respondError($e->getMessage(), 403);
        // }

        // $email = $this->request->getVar('email') ?? null;
        // $code = $this->request->getVar('code') ?? null;

        // try {
        //     $verified = $this->otp->verify($email, $code);

        //     if (!$verified) {
        //         return $this->respondError(lang('Otp.invalid'), 400);
        //     }
        //     return $this->respondSuccess(lang('Otp.verified'), [
        //         'redirectTo' => '/'
        //     ]);
        // } catch (\Throwable $e) {
        //     return $this->respondError($e->getMessage(), 403);
        // }
    }

    public function logout() {
        $session = new UserSessionService();

        try {
            $token = $this->request->getCookie('jwt');
            if (!$token) {
                return $this->respondError(lang('Session.destroy'), 403);
            }
            $decoded = $this->validateToken($token);
            $delete = $session->destroySession($decoded->uid);
            
            if (!$delete) {
                return $this->respondError(lang(line: 'Session.not_delete'));
            }
            $session->clearCookie();

            return $this->respondSuccess(lang('Session.disconnected'), [
                'redirectTo' => '/login'
            ]);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 500);;
        }
    }

    public function validateToken(?string $token) {
        return $this->token->validateToken($token);
    }
}
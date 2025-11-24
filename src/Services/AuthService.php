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

    public function __construct()
    {
        parent::__construct();
        $this->valid = new AuthConfig();
    }

    public function login()
    {
        if (session()->get('session_user_id')) {
            return $this->respondError(lang('Session.is_connected'), 403, [
                'redirectTo' => '/logout-remote'
            ]);
        }

        $payload = $this->get_data_from_post();
        $pipeline = new AuthLib();

        try {
            if (!$this->validate($this->valid->auth)) {
                return $this->respondError($this->validation->getErrors());
            }
            $payload['ip_address'] = $this->request->getIPAddress();

            $result = $pipeline->handle($payload);

            // Si l'étape 2FA est requise mais non encore validée
            if (!empty($result['requires_2FA']) && empty($result['two_factor_checked'])) {
                return $this->respondError(lang('Otp.twofactor_required'), 403, [
                    'redirecTo' => '/2FA',
                    'user_id' => $result['user']->id
                ]);
            }
            return axprooResponse(200, 'Successfully Login', $result);

        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;

            return axprooResponse($code, $e->getMessage(), $payload);
        } catch (\Throwable $t) {
            return axprooResponse(500, $t->getMessage());
        }
    }

    public function verifyTwofactor()
    {
        if (!session()->get('2fa_pending') || !session()->get('2fa_user_id')) {
            return $this->respondError(lang('Auth.login.unauthorized'), 403, [
                'redirectTo' => '/login'
            ]);
        }

        $payload = $this->get_data_from_post();
        $pipeline = new AuthLib();

        try {
            // Validation des champs emails + code 2FA
            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }
            $payload['ip_address'] = $this->request->getIPAddress();

            // On suppose que $payload contien 'user_id' et 'two_factor_code'
            $user = $this->user_model->find(session()->get('2fa_user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }
            $payload['skip_password_check'] = true;
            $payload['user'] = $user;
            $payload['email'] = $user->email;

            // On relance le pipeline avec le code 2FA
            $result = $pipeline->handle($payload);

            session()->remove('2fa_pending');
            session()->remove('2fa_user_id');
            return $this->respondSuccess('Successfully Login', $result);
        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;
            return $this->respondError($e->getMessage(), $code, $payload);
        } catch (\Throwable $t) {
            return $this->respondError($t->getMessage(), 500);
        }
    }

    public function verifyEmail()
    {
        $payload = $this->get_data_from_post();
        $pipeline = new AuthLib();

        try {
            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }

            // On suppose que $payload contien 'user_id'
            $user = $this->user_model->find(session()->get('user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }
            $payload['user'] = $user;
            $payload['ip_address'] = $this->request->getIPAddress();

            $result = $pipeline->handle($payload);
            return $this->respondSuccess(lang('Otp.verified'), $result);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 403);
        }
        // $payload = $this->get_data_from_post();
        // $pipeline = new ValidEmailVerified();

        // try {
        //     if (!$this->validate($this->valid->code)) {
        //         return $this->respondError($this->validation->getErrors());
        //     }

        //     $result = $pipeline->handle($payload);
        //     return $this->respondSuccess(lang('Otp.verified'), $result);
        // } catch (\Throwable $e) {
        //     return $this->respondError($e->getMessage(), 403);
        // }
    }

    public function logout()
    {
        $session = new UserSessionService();

        try {
            $token = $session->getCookie('jwt');
            if (!$token) {
                return $this->respondError(lang('Session.destroy'), 403);
            }
            $delete = $session->destroySession($token);
            if (!$delete) {
                return $this->respondError(lang('Session.not_delete'));
            }

            $session->clearCookie();
            session()->destroy();

            return $this->respondSuccess(lang('Session.disconnected'), [
                'redirectTo' => '/login'
            ]);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 500);
        }
    }

    public function remoteLogout()
    {
        $session = session();

        if (!$session->get('session_user_id')) {
            return $this->respondError(lang('Auth.login.unauthorized'), 403, [
                'redirectTo' => '/login'
            ]);
        }
        $payload = $this->get_data_from_post();
        $pipeline = new AuthLib();

        try {
            $user = $this->user_model->find($session->get('session_user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }
            $payload['user'] = $user;
            $payload['skip_logout_remote'] = true;
            $payload['ip_address'] = $this->request->getIPAddress();

            $result = $pipeline->handle($payload);
            return $this->respondSuccess('Successfully login', $result);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 500);
        }
    }
}

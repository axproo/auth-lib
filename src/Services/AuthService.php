<?php

namespace Axproo\Auth\Services;

use Axproo\Auth\Config\Validation\AuthConfig;
use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Libraries\AuthLib;
use Axproo\Auth\Libraries\GenerateTotp;
use Axproo\Auth\Libraries\ValidEmailVerified;
use Axproo\Auth\Pipelines\AuthPipeline;
use Axproo\Auth\Pipelines\EmailPipeline;
use Axproo\Auth\Pipelines\LogoutPipeline;
use Axproo\Auth\Pipelines\TwofactorPipeline;

class AuthService extends BaseAuthService
{
    protected $valid;
    protected $payload;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->valid = new AuthConfig();
        $this->payload = $this->get_data_from_post();
        $this->payload['ip_address'] = $this->request->getIPAddress();
        $this->session = session();
    }

    public function login()
    {
        $pipeline = new AuthPipeline();

        try {
            if (!$this->validate($this->valid->auth)) {
                return $this->respondError($this->validation->getErrors());
            }
            $result = $pipeline->handle($this->payload);
            return $this->respondSuccess(lang('Auth.login.success'), $result);
        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;

            return $this->respondError($e->getMessage(), $code, $payload);
        } catch (\Throwable $t) {
            return $this->respondError($t->getMessage(), 500);
        }
        // if (session()->get('session_user_id')) {
        //     return $this->respondError(lang('Session.is_connected'), 403, [
        //         'redirectTo' => '/logout-remote'
        //     ]);
        // }

        // $payload = $this->get_data_from_post();
        // $pipeline = new AuthLib();

        // try {
        //     if (!$this->validate($this->valid->auth)) {
        //         return $this->respondError($this->validation->getErrors());
        //     }
        //     $payload['ip_address'] = $this->request->getIPAddress();

        //     $result = $pipeline->handle($payload);

        //     // Si l'étape 2FA est requise mais non encore validée
        //     if (!empty($result['requires_2FA']) && empty($result['two_factor_checked'])) {
        //         return $this->respondError(lang('Otp.twofactor_required'), 403, [
        //             'redirecTo' => '/2FA',
        //             'user_id' => $result['user']->id
        //         ]);
        //     }
        //     return axprooResponse(200, 'Successfully Login', $result);

        // } catch (AuthException $e) {
        //     $payload = $e->getPayload();
        //     $code = $e->getCode() ?: 403;

        //     return axprooResponse($code, $e->getMessage(), $payload);
        // } catch (\Throwable $t) {
        //     return axprooResponse(500, $t->getMessage());
        // }
    }

    public function verifyTwofactor() {
        $pipeline = new TwofactorPipeline();

        try {
            if (!$this->session->get('session_user_id')) {
                return $this->respondError(lang('Auth.login.unauthorized'), 500, [
                    'redirectTo' => '/login'
                ]);
            }

            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }

            $user = $this->user_model->find($this->session->get('session_user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }

            $this->payload['user'] = $user;
            $result = $pipeline->handle($this->payload);

            return $this->respondSuccess(lang('Otp.verified'), $result);
        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;
            return $this->respondError($e->getMessage(), $code, $payload);
        } catch (\Throwable $t) {
            return $this->respondError($t->getMessage(), 500);
        }
    }

    public function verifyTwofactor0()
    {
        // if (!session()->get('2fa_pending') || !session()->get('2fa_user_id')) {
        //     return $this->respondError(lang('Auth.login.unauthorized'), 403, [
        //         'redirectTo' => '/login'
        //     ]);
        // }

        // $payload = $this->get_data_from_post();
        // $pipeline = new AuthLib();

        // try {
        //     // Validation des champs emails + code 2FA
        //     if (!$this->validate($this->valid->code)) {
        //         return $this->respondError($this->validation->getErrors());
        //     }
        //     $payload['ip_address'] = $this->request->getIPAddress();

        //     // On suppose que $payload contien 'user_id' et 'two_factor_code'
        //     $user = $this->user_model->find(session()->get('2fa_user_id'));
        //     if (!$user) {
        //         return $this->respondError(lang('Users.missing'), 404);
        //     }
        //     $payload['skip_password_check'] = true;
        //     $payload['user'] = $user;
        //     $payload['email'] = $user->email;

        //     // On relance le pipeline avec le code 2FA
        //     $result = $pipeline->handle($payload);

        //     session()->remove('2fa_pending');
        //     session()->remove('2fa_user_id');
        //     return $this->respondSuccess('Successfully Login', $result);
        // } catch (AuthException $e) {
        //     $payload = $e->getPayload();
        //     $code = $e->getCode() ?: 403;
        //     return $this->respondError($e->getMessage(), $code, $payload);
        // } catch (\Throwable $t) {
        //     return $this->respondError($t->getMessage(), 500);
        // }
    }

    public function verifyEmail() {
        $pipeline = new EmailPipeline();

        try {
            if (!$this->session->get('session_user_id')) {
                return $this->respondError(lang('Auth.login.unauthorized'), 500, [
                    'redirectTo' => '/login'
                ]);
            }

            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }
            $user = $this->user_model->find($this->session->get('session_user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }
            $this->payload['user'] = $user;

            $result = $pipeline->handle($this->payload);
            return $this->respondSuccess(lang('Otp.verified'), $result);
        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;

            return $this->respondError($e->getMessage(), $code, $payload);
        } catch (\Throwable $th) {
            return $this->respondError($th->getMessage(), 500);
        }
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
        $pipeline = new LogoutPipeline();

        try {
            if (!$this->session->get('session_user_id')) {
                return $this->respondError(lang('Auth.login.unauthorized'), 500, [
                    'redirectTo' => '/login'
                ]);
            }

            if (!$this->validate($this->valid->code)) {
                return $this->respondError($this->validation->getErrors());
            }

            $user = $this->user_model->find($this->session->get('session_user_id'));
            if (!$user) {
                return $this->respondError(lang('Users.missing'), 404);
            }
            $this->payload['user'] = $user;
            $result = $pipeline->handle($this->payload);

            return $this->respondSuccess(lang('Auth.logout.success'), $result);
        } catch (\Throwable $e) {
            return $this->respondError($e->getMessage(), 500);
        }
    }
}

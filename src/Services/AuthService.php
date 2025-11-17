<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Config\Validation\AuthConfig;
use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Libraries\AuthLib;

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

    public function logout() {
        try {
            $token = $this->request->getCookie('jwt');
            if (!$token) {
                return axprooResponse(403, lang('Session.destroy'));
            }
            $decoded = $this->validateToken($token);

            $session = new UserSessionService();
            $session->destroySession($decoded->uid, $token);

            return axprooResponse(200, lang('Seesion.disconnected'), [
                'redirectTo' => '/login',
                'jwt' => $token
            ]);
        } catch (\Throwable $e) {
            return axprooResponse(500, $e->getMessage());
        }
    }

    public function validateToken(?string $token) {
        return $this->token->validateToken($token);
    }
}
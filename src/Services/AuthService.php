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
            $result = $pipeline->handle($payload);
            // return $this->respondSuccess('Successfully login', $result);
            return axprooResponse(200, 'Successfully Login', $result);
        } catch (AuthException $e) {
            $payload = $e->getPayload();
            $code = $e->getCode() ?: 403;

            // return $this->respondError($e->getMessage(), $code, $payload);
            throw new AuthException($e->getMessage(), $code, $payload);
        } catch (\Throwable $t) {
            throw new AuthException($t->getMessage());
        }
        // $data = $this->get_data_from_post();

        // // Validation des données
        // if (!$this->validate($this->valid->auth)) {
        //     return $this->respondError($this->validation->getErrors());
        // }

        // // Vérification du status et du mot de passe de l'utilisateur
        // $user = $this->model->findByEmail($data['email']);
        // $status = $this->checkStatus($user);
        // // $checkStatus = 

        // // $token = $this->token->generateToken([
        // //     'tenant' => $this->tenant->getTenantById($user->id),
        // //     'email' => $user->email,
        // //     'role' => $this->rules->getRoleById($user->role_id),
        // //     'redirect' => $this->redirectTo($user)
        // // ]);

        // // Vérification du mot de passe
        // if (!$this->hasher->verify_password($data['password'], $user->password)) {
        //     return $this->respondError(lang(line: 'Password.incorrect'));
        // }

        // return $this->respondSuccess('Success connexion', [
        //     'status' => $status
        //     // 'token' => $token,
        //     // 'redirect' => $this->redirectTo($user),
        // ]);
    }
}
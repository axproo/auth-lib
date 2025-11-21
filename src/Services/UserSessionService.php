<?php

namespace Axproo\Auth\Services;

use Axproo\Auth\Models\SessionsModel;
use CodeIgniter\I18n\Time;

class UserSessionService extends BaseAuthService
{
    private bool $secure = false;
    private int $expire = 3600;
    protected SessionsModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new SessionsModel();
        $this->secure = filter_var(getenv('SECURE_COOKIE'), FILTER_VALIDATE_BOOLEAN);
        $this->expire = (int) (getenv('JWT_EXPIRE') ?: 3600);
    }

    public function registerSession(string $token, object $user)
    {
        $data = [
            'user_id' => $user->id,
            'jwt_token' => $token,
            'user_ip' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent(),
            'last_activity' => Time::now()
        ];

        // Vérifier si la session existe
        $exists = $this->model->where('user_id', $user->id)->first();

        if ($exists) {
            $data['id'] = $exists->id;
        }
        $this->model->save($data);
    }

    public function validateSession(int $userId, string $token): bool
    {
        $row = $this->model->where('user_id', $userId)->first();

        // Aucun enregistrement -> autoriser et enregistrer la session
        if (!$row) {
            return true;
        }

        // Comparer le token avec celui stocké
        if ($row->jwt_token !== $token) {
            return false;
        }
        return true;
    }

    public function destroySession(string $token): bool
    {
        return $this->model->where('jwt_token', $token)->delete();
    }

    public function getCookie(string $cookie)
    {
        return $this->request->getCookie($cookie ?? 'jwt');
    }

    public function setCookie($token)
    {
        $this->response->setCookie([
            'name'      => 'jwt',
            'value'     => $token,
            'expire'    => time() + $this->expire, // 24h par défaut
            'httponly'  => true,
            'secure'    => $this->secure, // mettre à true en production avec HTTPS
            'path'      => '/',
            'samesite'  => 'Lax' // Lax ou Strict pour plus de sécurité
        ]);
    }

    public function clearCookie()
    {
        $this->response->setCookie([
            'name' => 'jwt',
            'value' => '',
            'expire' => time() - 3600,
            'httponly' => true,
            'secure' => $this->secure, // Mettre à true en production avec HTTPS
            'path' => '/',
            'samesite' => 'Lax' // Lax ou Strict pour plus de sécurité
        ]);
        $this->response->setHeader('Clear-Site-Data', '"cookies"');
    }
}

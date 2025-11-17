<?php 

namespace Axproo\Auth\Services;

use Axproo\Auth\Models\SessionsModel;
use CodeIgniter\I18n\Time;

class UserSessionService extends BaseAuthService
{
    protected SessionsModel $model;

    public function __construct() {
        parent::__construct();
        $this->model = new SessionsModel();
    }

    public function registerSession(string $token, object $user) {
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

    public function validateSession(int $userId, string $token) : bool {
        $row = $this->model->where('user_id', $userId)->first();

        // Aucun enregistrement -> autoriser et enregistrer la session
        if (!$row) return true;

        // Comparer le token avec celui stocké
        if ($row->jwt_token !== $token) {
            return false;
        }
        return true;
    }

    public function destroySession(int $userId) : bool {
        return $this->model->delete($userId);
    }

    public function setCookie($token, $expire = 86400) {
        $this->response->setCookie([
            'name'      => 'jwt',
            'value'     => $token,
            'expire'    => $expire, // 24h par défaut
            'httponly'  => true,
            'secure'    => false, // mettre à true en production avec HTTPS
            'path'      => '/',
            'samesite'  => 'lax' // Lax ou Strict pour plus de sécurité
        ]);
        $this->response->setHeader('Clear-Site-Data', '"cookies"');
    }
}
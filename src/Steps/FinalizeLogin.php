<?php 

namespace Axproo\Auth\Steps;

use Axproo\Auth\Exceptions\AuthException;
use CodeIgniter\I18n\Time;

class FinalizeLogin extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start FinalizeLogin");

        $token = session()->get('token') ?? null;
        $user = $this->getUserData($data);

        $user->last_login_at = Time::now();
        $user->ip_address = $data['ip_address'] ?? null;

        if (!$token) {
            throw new AuthException(lang('Token.not_found'));
        }

        $this->usersModel->save($user);
        $this->session->registerSession($token, $user);

        session()->destroy();
        log_message("debug", "End FinalizeLogin");
        return $data;
    }
}
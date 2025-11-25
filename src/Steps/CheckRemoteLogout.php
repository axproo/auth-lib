<?php 

namespace Axproo\Auth\Steps;

class CheckRemoteLogout extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start CheckRemoteLogout");
        
        $user = $this->getUserData($data);
        $token = $this->generateToken($user);

        $this->setCookies($token);

        $data['token'] = $token;
        log_message("debug", "End CheckRemoteLogout");
        return $data;
    }
}
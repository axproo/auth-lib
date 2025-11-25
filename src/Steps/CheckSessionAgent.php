<?php 

namespace Axproo\Auth\Steps;

class CheckSessionAgent extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start CheckSessionAgent");
        // Récupérer les données de l'utilisateur
        $user = $this->getUserData($data);

        // Générer et vérifier le token
        $token = $this->generateToken($user);
        $this->validateSession($user->id, $token);
        
        session()->set('session_user_id', $user->id);
        session()->set("token", $token);
        log_message("debug", "End CheckSessionAgent\n");
        return $data;
    }
}
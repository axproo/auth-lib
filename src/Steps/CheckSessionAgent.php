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

        // Générer le token
        $token = $this->token->generateToken([
            'uid' => $user->id,
            'tenant' => $this->tenant->getTenantById($user->id),
            'email' => $user->email,
            'fullname' => "{$user->first_name} {$user->last_name}",
            'role' => $this->rules->findByUser($user->id),
            'status' => $user->status,
            'two_factor_enabled' => $this->convertToBool($user->two_factor_enabled)
        ]);
        $this->validateSession($user->id, $token);
        
        session()->set("token", $token);
        log_message("debug", "End CheckSessionAgent\n");
        return $data;
    }
}
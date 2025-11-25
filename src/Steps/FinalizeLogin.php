<?php 

namespace Axproo\Auth\Steps;

class FinalizeLogin extends BaseStep
{
    public function __construct() {
        parent::__construct();
    }

    public function handle(array $data) : array {
        log_message("debug", "Start FinalizeLogin");

        $token = $data['token'] ?? null;
        $user = $this->getUserData($data);

        
        log_message("debug", "End FinalizeLogin");
        return $data;
    }
}
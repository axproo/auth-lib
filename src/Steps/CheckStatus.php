<?php 

namespace Axproo\Auth\Steps;

class CheckStatus
{
    public function handle(array $data) : array {
        log_message("debug", "Step 2: CheckStatus");
        

        log_message("debug", "End Step 2\n");
        return $data;
    }
}
<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckRemoteLogout;

class LogoutPipeline extends BasePipeline
{
    protected array $steps = [
        CheckRemoteLogout::class
    ];

    public function __construct() {
        parent::__construct($this->steps);
    }

    public function handle(array $payload) : array {
        return $this->setHandle($payload);
    }
}
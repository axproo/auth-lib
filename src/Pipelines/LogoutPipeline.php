<?php

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckRemoteLogout;
use Axproo\Auth\Steps\CheckTwoFactorValidate;

class LogoutPipeline extends BasePipeline
{
    protected array $steps = [
        CheckTwoFactorValidate::class,
        CheckRemoteLogout::class
    ];

    public function __construct()
    {
        parent::__construct($this->steps);
    }

    public function handle(array $payload): array
    {
        return $this->setHandle($payload);
    }
}

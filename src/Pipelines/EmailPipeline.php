<?php

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckEmailValidate;
use Axproo\Auth\Steps\CheckSessionAgent;
use Axproo\Auth\Steps\CheckTwoFactor;

class EmailPipeline extends BasePipeline
{
    protected array $steps = [
        CheckEmailValidate::class,
        CheckSessionAgent::class,
        CheckTwoFactor::class
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

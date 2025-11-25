<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckTwoFactorValidate;
use Axproo\Auth\Steps\FinalizeLogin;

class TwofactorPipeline extends BasePipeline
{
    protected array $steps = [
        CheckTwoFactorValidate::class,
        FinalizeLogin::class
    ];

    public function __construct() {
        parent::__construct($this->steps);
    }
    
    public function handle(array $payload) : array {
        return $this->setHandle($payload);
    }
}
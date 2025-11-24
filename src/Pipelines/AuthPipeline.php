<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Steps\CheckStatus;
use Axproo\Auth\Steps\CheckUserExists;

class AuthPipeline extends BasePipeline
{
    protected array $steps = [
        CheckUserExists::class,
        CheckStatus::class
    ];

    public function __construct() {
        parent::__construct($this->steps);
    }

    public function handle(array $payload) : array {
        return $this->setHandle($payload);
    }
}
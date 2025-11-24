<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Exceptions\AuthException;
use Axproo\Auth\Steps\CheckStatus;
use Axproo\Auth\Steps\CheckUserExists;
use ReflectionClass;

class AuthPipeline extends BasePipeline
{
    protected array $step = [
        CheckUserExists::class,
        CheckStatus::class
    ];

    public function __construct() {
        parent::__construct($this->step);
    }

    public function handle(array $payload) : array {
        return $this->handle($payload);
    }
}
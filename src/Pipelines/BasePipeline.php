<?php 

namespace Axproo\Auth\Pipelines;

use Axproo\Auth\Exceptions\AuthException;
use ReflectionClass;

abstract class BasePipeline
{
    protected array $step = [];

    public function __construct(array $step = []) {
        $this->step = $step;
        if (empty($this->step)) {
            throw new AuthException("Step not found");
        }
    }
    
    protected function setHandle(array $payload) : array {
        $data = $payload;

        foreach ($this->step as $stepClass) {
            if (!class_exists($stepClass)) {
                throw new AuthException("Step class {$stepClass} not found", 500);
            }

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            if (!method_exists($step, 'handle')) {
                throw new AuthException("Step class {$stepClass} must implement handle()", 500);
            }

            $data = $step->handle($data);

            if (!\is_array($data)) {
                throw new AuthException("Step class {$stepClass} must return an array", 500);
            }
        }
        return $data;
    }
}
<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use ReflectionClass;

class AuthLib
{
    protected array $steps = [
        CheckUserExists::class,
        CheckStatus::class
    ];

    public function handle(array $payload) : array {
        $data = $payload;

        foreach ($this->steps as $stepClass) {
            if (!class_exists($stepClass)) {
                throw new AuthException("Step class {$stepClass} not found", 500);
            }

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            if (!method_exists($step, 'handle')) {
                throw new AuthException("Step class {$stepClass} must implement handle()", 500);
            }

            $data = $step->handle($data);

            if (!is_array($data)) {
                throw new AuthException("Step {$stepClass} must return an array", 500);
            }
        }
        return $data;
    }
}
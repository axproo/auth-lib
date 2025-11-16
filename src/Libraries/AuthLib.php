<?php 

namespace Axproo\Auth\Libraries;

use Axproo\Auth\Exceptions\AuthException;
use ReflectionClass;

class AuthLib
{
    protected array $steps = [
        CheckUserExists::class
    ];

    public function handle(array $payload) : array {
        $data = $payload;

        foreach ($this->steps as $stepClass) {
            if (!class_exists($stepClass)) {
                throw new AuthException("Step class {$stepClass} not found", 500);
            }

            $ref = new ReflectionClass($stepClass);
            $step = $ref->newInstance();

            $data = $step->handle($data);
        }
        return $data;
    }
}